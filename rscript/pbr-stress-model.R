

inverse.possion <- function(x, lambda, target.percent=0.1, possion.inc=0.1, possion.iteration=10000) {
  # Computes the first value to achieve target likelihood to hit given an average in a possion distribution
  # 
  # Args: 
  #   x: scaler starting point to increment to determine the likelihood of occuring
  #   lambda: average of the values
  #   target.percent: target likelihood to find something happening
  #   possion.inc: increment value to keep trying to find a given target
  #   possion.iteration: number of attempts to find a given target, incrementing each time
  #
  #
  # Error handling:
  #   Stop is called if the function could not reach that percentage over this iteration
  
  if (possion.iteration == 0) {
    stop("too many iterations")
  }
  
  actual <- ppois(x, lambda=lambda, lower.tail = FALSE)  
  
  if (actual <= target.percent) {
    return (x)
  }
  else {
    return (inverse.possion(x+possion.inc, lambda, target.percent, possion.inc, possion.iteration-1))
  }
}

#inverse.possion(4, 4, iteration=1000, target=0.99, inc=0.01)
burst.calc <- function(client.call.sec, client.agent.occupancy, client.total.agent, client.skill.ratio, target.percent, possion.iteration=10000) {
  matr.pbr.message.rate <- 1000   # max messages per second     
  matr.acceptable.burst <- 0.05   # risk assessment for any second to burst
  
  calc.message.per.call <- 11+(7*client.skill.ratio)
  calc.calls.per.agent.hour <- (3600 / client.call.sec) * client.agent.occupancy
  calc.message.per.hour <- calc.message.per.call * calc.calls.per.agent.hour * client.total.agent
  calc.calls.per.hour <- calc.message.per.hour / calc.message.per.call
  calc.message.per.sec <- calc.message.per.hour / 3600
  calc.calls.per.sec <- calc.calls.per.hour / 3600
  
  out.burst.message.sec <- inverse.possion(calc.message.per.sec, calc.message.per.sec, target.percent=target.percent, possion.inc=0.1, possion.iteration=possion.iteration)
  out.burst.calls.per.sec <- out.burst.message.sec / calc.message.per.call
  
  return (out.burst.message.sec)
}


calc.edge <- function(base.vector, target.edge, pos=1, target.percent=0.01, value.inc=1.0, possion.iteration=10000) {
  out.edge <- 0
  temp.vector <- base.vector
  while (out.edge < target.edge) {
    out.edge <- burst.calc(temp.vector[1], 1.0, temp.vector[2] , temp.vector[3], target.percent, possion.iteration)
    temp.vector[pos] <- temp.vector[pos] + value.inc
  }
  return (temp.vector[pos])
}

# allow 10000 nested recurrsions
options(expressions=100000)

# base configs
base.client.call.sec <- 300
base.client.total.agent <- 1000
base.client.skill.ratio <- 3
target.burst.message.per.sec <- 1000
target.percent <- 0.01
base.vars = c(base.client.call.sec, base.client.total.agent, base.client.skill.ratio)

# calculate min average call length
min.client.call.sec    <- calc.edge(base.vars, target.edge=target.burst.message.per.sec, pos=1, value.inc=-1.0, target.percent=target.percent)
max.client.total.agent <- calc.edge(base.vars, target.edge=target.burst.message.per.sec, pos=2, value.inc=1.0,target.percent=target.percent)
max.client.skill.ratio <- calc.edge(base.vars, target.edge=target.burst.message.per.sec, pos=3, value.inc=1.0,target.percent=target.percent)

scatter.matrix <- matrix(ncol=3)

# iterate the range and find all within a certain threshold within the target.burse.message.per.sec
threshold <- 3
error.count <- 0
for (x in min.client.call.sec:base.client.call.sec) { 
    for (y in base.client.total.agent:max.client.total.agent ) { 
      for (z in base.client.skill.ratio:max.client.skill.ratio ) { 
        #print(paste(x, y, z))
        
        # try to calculate burst but may run into memory issues        
        res <- try ( burst <- burst.calc(x,1.0, y,z, target.percent, possion.iteration=1), silent=TRUE )
        
        if(inherits(res, "try-error")) {
          #error handling code, maybe just skip this iteration using
          error.count <- error.count + 1
        
        } else {
          
          # check if the burst is reasonably close to the target 
          diff <- target.burst.message.per.sec - burst 
          if (diff <= threshold && diff >= 0) {
            scatter.matrix <- rbind(scatter.matrix, c(x,y,z)) 
          }
        }         
    }
  }
}

colnames(scatter.matrix) <- c('average.call.length', 'concurrent.agents', 'average.skill.per.agent')

# sample the matrix 
set.seed(40)
sam <- sample(nrow(scatter.matrix),size=1000,replace=FALSE)
sample.matrix <- scatter.matrix[sam,]

# append max values to sample matrix
sample.matrix <- rbind(sample.matrix, c(min.client.call.sec, base.client.total.agent, base.client.skill.ratio))
sample.matrix <- rbind(sample.matrix, c(base.client.call.sec, max.client.total.agent, base.client.skill.ratio))
sample.matrix <- rbind(sample.matrix, c(base.client.call.sec, base.client.total.agent, max.client.skill.ratio))
#head(sample.matrix)

#install.packages('rgl')
library('rgl')
plot3d(sample.matrix[,'average.call.length'], 
       sample.matrix[,'concurrent.agents'], 
       sample.matrix[,'average.skill.per.agent'],
       xlab="Call Length",
       ylab="Agent Count",
       zlab="Skills / Agent",
       size=1,
       type="s",
       col='red'
)


# testing
res <- try ( x <- burst.calc(128,1.0, 32, 34, 0.01), silent=TRUE )

  
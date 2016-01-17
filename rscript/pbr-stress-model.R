

inverse.possion <- function(x, lambda, target=0.1, inc=0.1, iteration=10000) {
  # Computes the first value to achieve target likelihood to hit given an average in a possion distribution
  # 
  # Args: 
  #   x: scaler starting point to increment to determine the likelihood of occuring
  #   lambda: average of the values
  #   target: target likelihood to find something happening
  #   inc: increment value to keep trying to find a given target
  #   iteration: number of attempts to find a given target, incrementing each time
  #
  #
  # Error handling:
  #   Stop is called if the function could not reach that percentage over this iteration
  
  if (iteration == 0) {
    stop("too many iterations")
  }
  
  actual <- ppois(x, lambda=lambda, lower.tail = FALSE)  
  
  if (actual <= target) {
    return (x)
  }
  else {
    return (inverse.possion(x+inc, lambda, target, inc, iteration-1))
  }
}

#inverse.possion(4, 4, iteration=1000, target=0.99, inc=0.01)
burst.calc <- function(client.call.sec, client.agent.occupancy, client.total.agent, client.skill.ratio, target) {
  matr.pbr.message.rate <- 1000   # max messages per second     
  matr.acceptable.burst <- 0.05   # risk assessment for any second to burst
  
  calc.message.per.call <- 11+(7*client.skill.ratio)
  calc.calls.per.agent.hour <- (3600 / client.call.sec) * client.agent.occupancy
  calc.message.per.hour <- calc.message.per.call * calc.calls.per.agent.hour * client.total.agent
  calc.calls.per.hour <- calc.message.per.hour / calc.message.per.call
  calc.message.per.sec <- calc.message.per.hour / 3600
  calc.calls.per.sec <- calc.calls.per.hour / 3600
  
  out.burst.message.sec <- inverse.possion(calc.message.per.sec, calc.message.per.sec, target=target, inc=0.1)
  out.burst.calls.per.sec <- out.burst.message.sec / calc.message.per.call
  
  return (out.burst.message.sec)
}


calc.edge <- function(base.vector, target.edge, pos=1, target.percent=0.01, inc=1.0) {
  out.edge <- 0
  temp.vector <- base.vector
  while (out.edge < target.edge) {
    out.edge <- burst.calc(temp.vector[1], 1.0, temp.vector[2] , temp.vector[3], target.percent)
    temp.vector[pos] <- temp.vector[pos] + inc
  }
  return (temp.vector[pos])
}

base.client.call.sec <- 300
base.client.total.agent <- 1000
base.client.skill.ratio <- 3
target.burst.message.per.sec <- 1000
target.percent <- 0.01
base.vars = c(base.client.call.sec, base.client.total.agent, base.client.skill.ratio)

# calculate min average call length
min.client.call.sec <- calc.edge(base.vars, target.edge=target.burst.message.per.sec, pos=1, inc=-1.0, target.percent=target.percent)
max.client.total.agent <- calc.edge(base.vars, target.edge=target.burst.message.per.sec, pos=2, inc=1.0,target.percent=target.percent)
max.client.skill.ratio <- calc.edge(base.vars, target.edge=target.burst.message.per.sec, pos=3, inc=1.0,target.percent=target.percent)

scatter.matrix <- matrix(ncol=3)

# iterate the range and find all within a certain threshold within the target.burse.message.per.sec
threshold <- 3
for (x in min.client.call.sec:base.client.call.sec ) { 
  for (y in base.client.total.agent:max.client.total.agent ) { 
    for (z in base.client.skill.ratio:max.client.skill.ratio ) { 
      burst <- burst.calc(x,1.0, y,z, target=target.percent)
      #print(burst)
      diff <- target.burst.message.per.sec - burst 
      if (diff <= threshold && diff >= 0) {
        scatter.matrix <- rbind(scatter.matrix, c(x,y,z)) 
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
burst.calc(33,1.0, 1000, 5, target.burst.message.per.sec)

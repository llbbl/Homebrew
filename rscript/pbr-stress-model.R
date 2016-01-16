

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


calc.edge <- function(baseVarX, staticY, staticZ, target.edge, target.percent=0.01, inc=1.0) {
  out.edge <- 0
  initialVar <- baseVarX
  while (out.edge < target.edge) {
    out.edge <- burst.calc(initialVar, 1.0, staticY , staticZ, target.percent)
    initialVar <- initialVar + inc
  }
  return (initialVar)
}

base.client.call.sec <- 300
base.client.total.agent <- 1000
base.client.skill.ratio <- 3
target.burst.message.per.sec <- 1000


min.client.call.sec <- calc.edge(base.client.call.sec, base.client.total.agent, base.client.skill.ratio, target.edge=target.burst.message.per.sec, inc=-1.0)
min.client.call.sec <- calc.edge(base.client.call.sec, base.client.total.agent, base.client.skill.ratio, target.edge=target.burst.message.per.sec, inc=-1.0)

# calculate min average call length
out.burst.message.per.sec <- 0
int.call.sec <- base.client.call.sec
matrix.call.sec <- matrix(ncol=3)
while(out.burst.message.per.sec < target.burst.message.per.sec) {
  out.burst.message.per.sec <- burst.calc(int.call.sec, 1.0, base.client.total.agent , base.client.skill.ratio, 0.01)
  int.call.sec <- int.call.sec - 1
  matrix.call.sec <- rbind(matrix.call.sec, c(int.call.sec, base.client.total.agent, base.client.skill.ratio ))
}
min.client.call.sec <- int.call.sec 

# calculate max agent count
out.burst.message.per.sec <- 0
int.total.agent <- base.client.total.agent
matrix.total.agent <- matrix(ncol=3)
while(out.burst.message.per.sec < target.burst.message.per.sec) {
  out.burst.message.per.sec <- burst.calc(base.client.call.sec, 1.0, int.total.agent, base.client.skill.ratio, 0.01)
  int.total.agent <- int.total.agent + 1
  matrix.total.agent <- rbind(matrix.total.agent, c(base.client.call.sec, int.total.agent, base.client.skill.ratio ))
}
max.client.total.agent <- int.total.agent

# calculate max average kill count
out.burst.message.per.sec <- 0
int.skill.ratio  <- base.client.skill.ratio 
matrix.skill.ratio <- matrix(ncol=3)
while(out.burst.message.per.sec < target.burst.message.per.sec) {
  out.burst.message.per.sec <- burst.calc(base.client.call.sec, 1.0, base.client.total.agent, int.skill.ratio, 0.01)
  int.skill.ratio<- int.skill.ratio+ 1
  matrix.skill.ratio <- rbind(matrix.skill.ratio, c(base.client.call.sec, base.client.total.agent, int.skill.ratio ))
}
max.client.skill.ratio <- int.skill.ratio 

# build matrix of max plots

matrix.plot <- matrix(ncol=3)
matrix.plot <- rbind(matrix.plot, c(min.client.call.sec, base.client.total.agent, base.client.skill.ratio))
matrix.plot <- rbind(matrix.plot, c(base.client.call.sec, max.client.total.agent, base.client.skill.ratio))
matrix.plot <- rbind(matrix.plot, c(base.client.call.sec, base.client.total.agent, max.client.skill.ratio))

#matrix.plot <- rbind(matrix.plot, matrix.skill.ratio, matrix.total.agent, matrix.call.sec )
matrix.plot <- matrix.plot[-1,]
colnames(matrix.plot) <- c('average.call.per.sec', 'concurrent.agents', 'average.skill.per.agent')


matrix.plot[,'average.skill.per.agent']

# plot
#install.packages('rgl')
#library('graphics')
library('rgl')
plot3d(matrix.plot[,'average.call.per.sec'], 
      matrix.plot[,'concurrent.agents'], 
      matrix.plot[,'average.skill.per.agent'],
      size=2,
      type="s",
      col='green'
      )
coords <- xyz.coords(matrix.plot[,'average.call.per.sec'], 
           matrix.plot[,'concurrent.agents'], 
           matrix.plot[,'average.skill.per.agent'])
planes3d(coords)
open3d()

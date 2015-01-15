cube <- function(x, n) {
        x^3
}
cube(3)

x <- 1:10
if(x > 5) {
        x <- 0
}

# scoping craziness
f <- function(x) {
        g <- function(y) {
                y + z
        }
    
        t <- x + g(x)
        z <- 4        
        x + g(x)
}
z <- 10
f(3)
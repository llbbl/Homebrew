require(grDevices) # for colours
tN <- table(Ni <- stats::rpois(100, lambda=5))
r <- barplot(tN, col=rainbow(20))

#- type = "h" plotting *is* 'bar'plot
lines(r, tN, type='h', col='red', lwd=2)

barplot(tN, space = 1.5, axisnames=FALSE,
        sub = "barplot(..., space= 1.5, axisnames = FALSE)")
is.matrix(VADeaths)
barplot(VADeaths, plot = FALSE)
barplot(VADeaths, plot = FALSE, beside = TRUE)
mp <- barplot(VADeaths) # default
tot <- colMeans(VADeaths)
text(mp, tot + 3, format(tot), xpd = TRUE, col = "blue")
barplot(VADeaths, beside = TRUE,
        col = c("lightblue", "mistyrose", "lightcyan",
                "lavender", "cornsilk"),
        legend = rownames(VADeaths), ylim = c(0, 100))
title(main = "Death Rates in Virginia", font.main = 4)

hh <- t(VADeaths)[, 5:1]
mybarcol <- "gray20"
mp <- barplot(hh, beside = TRUE,
              col = c("lightblue", "mistyrose",
                      "lightcyan", "lavender"),
              legend = colnames(VADeaths), ylim= c(0,100),
              main = "Death Rates in Virginia", font.main = 4,
              sub = "Faked upper 2*sigma error bars", col.sub = mybarcol,
              cex.names = 1.5)
segments(mp, hh, mp, hh + 2*sqrt(1000*hh/100), col = mybarcol, lwd = 1.5)
stopifnot(dim(mp) == dim(hh))# corresponding matrices
mtext(side = 1, at = colMeans(mp), line = -2,
      text = paste("Mean", formatC(colMeans(hh))), col = "red")


### Ticket ticket count
feedback <- c(1, 5, 7, 9, 3)
hold <- c(6, 28, 11, 3, 5)
resolved <- c(15, 4, 29, 33, 18)
validated <- c(3, 1, 5, 7, 8)
open <- c(5, 34, 22, 11, 3)
tickets = c(open, validated, resolved, hold, feedback)
rnames = c("12.12", "12.13", "12.14", "12.15", "12.16")
cnames = c("open", "validated", "resolved", "hold", "feedback")
mat <- matrix(tickets, nrow = length(cnames), ncol = length(rnames), byrow = TRUE, dimnames = list(rnames, 
                                                                                                 cnames))
mat

barplot(mat, names.arg=rnames, legend=cnames)
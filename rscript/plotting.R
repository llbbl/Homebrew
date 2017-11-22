data(iris)
names(iris)
with(iris, plot(Sepal.Length, Sepal.Width))
title("Sepal Length vs Width")

pdf("iris.graph.pdf")
with(iris, plot(Sepal.Length, Sepal.Width))
dev.off()




dose <- c(20, 40, 40, 45, 65)
drugA <- c(16, 20, 27, 40, 60)
drugB <- c(15, 18, 25, 31, 40)

plot(dose, drugA, type="b")

with(iris, plot(Sepal.Length, Petal.Width, main="Iris Plot", xlab = "Sepal Length", 
                ylab = "Pedal Width"))

opar <- par(no.readonly=TRUE)
par(lty=2, pch=17)
plot(dose, drugA, type="b")

par(pin=c(2,3))
par(lwd=2, cex=1.5)
par( cex.axis=0.75, font.axis=3)
plot(dose, drugA, type="b", pch=19, lty=2, col="red")
plot(dose, drugB, type="b", pch=23, lty=6, col="blue", bg="green")
colors()

x <- c(1:10)
y <- x
z <- 10/x

par(mar=c(5,4,4,8) + 0.1)
plot( x, y, type="b", pch=21, col="red", yaxt="n", lty=3, ann=FALSE)

lines(x, z, type="b", pch=22, col="blue", lty=2)
axis(2, at=x, labels=x, col.axis="red", las=2)
axis(4, at=z, labels, round(z, digits=2s))

demo(graphics)

par(opar)
tickets.completed <- c(4, 182, 102, 115, 212, 22)
names(tickets.completed) <- c("H", "C", "P", "N", "E", "CO")
ticket.colors <- c("purple", "green", "pink", "white", "brown", "red")
pie(tickets.completed, col=ticket.colors)
title(main="Completed in 2012", cex.main = 1.8, font.main = 1)
barplot(tickets.completed, names.arg=names(tickets.completed), col=ticket.colors)
lines(tickets.completed, type="b", col="red")

tickets.
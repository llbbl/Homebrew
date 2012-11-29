x1 <- c(1, 9, 3, 5, 7, 5, 2, 4)
x2 <- c(4, 9, 8, 5, 7, 2, 6, 1)
x1/12
x1 + x2

weight = c(1, 2, 3, 4, 1, 2, 3, 4)
height = c(1, 2, 3, 4, 1, 2, 3, 4)
gender <- c("male", "female", "male", "male", "male", "female", "male", "male")
logical <- gender == "female"

gender[logical]
height[logical]

y <- matrix(1:20, nrow = 5, ncol = 4)

cells <- c(1, 26, 24, 68)
rnames <- c("R1", "R2")
cnames <- c("C1", "C2")
mat <- matrix(cells, nrow = length(rnames), ncol = length(cnames), byrow = TRUE, dimnames = list(rnames, 
    cnames))

patientId <- c(1, 2, 3, 4)
age <- c(25, 34, 28, 52)
diabetes <- c("Type1", "Type2", "Type1", "Type2")
status <- c("Poor", "Improved", "Excellent", "Poor")
patientdata <- data.frame(patientId, age, diabetes, status)
patientdata[, 1]
patientdata[1, ]  #
patientdata[c(1, 4), 2]  ####

install.packages('formatR')
library(formatR)
f = "C:\\Development\\Homebrew\\rscript\\intro_exercise.R"
tidy.source(f, file = f)

id <- c(5, 6, 7, 8, 9)
age <- c(23, 32, 41, 52, 43)
eye <- c("brown", "blue", "brown", "hazel", "green")
hair <- c("brown", "blue", "brown", "hazel", "green")
demo = data.frame(id, age, eye, hair)
mean(demo$age) 


# 2.5
str(patientdata)
summary(patientdata)

# 2.7
g <- "My First List"
h <- c(25, 26, 18, 39);
j <- matrix(1:10, nrow=5);
k <- c("one", "two", "three");
mylist <- list(title=g, age=h, j, k)
mylist[[3]]

data(mtcars)
str(mtcars)
names(mtcars)
class(mtcars)
is.vector(mtcars$hp)
rownames(mtcars)
mtcars$id <- rownames(mtcars)
with(mtcars, mean(hp))

names(mtcars)
levels(factor(mtcars$am))
unique(mtcars$am)

head(mtcars$am);

factor(mtcars$am, c(0, 1), c("No", "Yes"))

data(iris)
names(iris)
length(iris)
length(rownames(iris))
s <- iris[c(25,26,10, 30),]
s
n <- data.frame(s$Species, s$Sepal.Width)
n

head(iris)
class(rownames(iris))
str(iris)

install.packages("car")
mtcars$id <- factor(mtcars$id)
mtcars$hp
cut(mtcars$hp, breaks=c(110, 175), include.lowest=TRUE)

head(mtcars)

mtcars$cyl[mtcars$cyl> 4 & mtcars$cyl < 8] 

x <- rep(1:3, 3)
x
col <- c(1,2)
recode(x, "c(1,2)='A'; else='B'")
names(mtcars)
nm <- names(mtcars)
nm

install.packages('reshape')
library(reshape)

names(mtcars)
new_mtcars <- rename(mtcars, c(wt="weight", cyl="cylinders"))
head(new_mtcars)

is.na(new_mtcars[c("vs")])

na.omit(new_mtcars)

# dates   ... try "luper date" package
dates <- as.Date(c("2012-12-01", "2012-01-12"))
dates <- as.Date(c("12/01/2012", "01/12/2012"), format="%m/%d/%Y")
dates
format(dates, "%B, %d %Y")

install.packages('lubridate')
library(lubridate)

lubridate::stamp
reshape::stamp

new_mtcars$dates <- NA

library(plyr)
arrange(new_mtcars, weight, desc(hp))

which(new_mtcars$weight > 1.5 & new_mtcars$weight < 2)
subset(new_mtcars, weight > 1.5 & weight < 2)

# sampling
new_mtcars[sample(1:nrow(new_mtcars), (.3 * nrow(new_mtcars)),replace = FALSE),]

# merging, look at stack overflow
merge() #all=True, all.x=True (left join), all.y=TRUE = (right join)
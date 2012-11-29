#########################
######################### 
######################### Test file

weight = c(1, 2, 3, 4)
height = c(1, 2, 3, 4)

constant <- 703
bmi <- (weight/(height^2)) * constant
bmi

gender = c("male", "female")
gender
class(weight)

logical <- gender == "male"
logical
gender[logical]


plot(height, weight)

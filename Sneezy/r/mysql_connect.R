require(RMySQL)
m<-MySQL()
summary(m)
con<-dbConnect(m, dbname = "sneezy", host="localhost", port=8889, user="---", pass="--") # in case you are using MAMP 
vomits = dbGetQuery(con, "select * from Reaction as r join ReactionType rt on rt.ReactionTypeId = r.ReactionTypeId where r.IsDeleted = 0 and rt.ReactionName = 'Vomit'")
vomits$ReactionDate
#install.packages("lubridate")
justDates <- as.Date(vomits$ReactionDate)
justDates
#strptime(justDates, "%Y-%m-%d")
#library(lubridate)
justDates <- parse_date_time(justDates, "%Y-%m-%d")
is.POSIXt(justDates)
# number of
justWeeks <- format(justDates, "%Y-%W")
justWeeks

#library(plyr)

#barplot(justWeeks, main="Week", xlab="Number of Vomits")
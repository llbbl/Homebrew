#
# This is an attempt to port PlancakeEmailParser.php to R
# @url https://github.com/plancake/official-library-php-email-parser/find/master
# Similarly, this should have a GPL license

email.load <- function(f) {
}


# execute stuff
#Sys.setlocale('LC_ALL','C') 
setwd('/home/cmack/Homebrew/Email')
f<-'Example/REExample.txt'
singleString <- paste(readLines(f), collapse="\n")
raw <-strsplit(singleString, "\n|\r")
raw<-raw[[1]]
headerEnd <- which(raw == "")[1]
header <- raw[seq(1,headerEnd - 1)]
body <- raw[seq(headerEnd + 1, length(raw) - 1)]


#
# This is an attempt to port PlancakeEmailParser.php to R
# @url https://github.com/plancake/official-library-php-email-parser/find/master
# Similarly, this should have a GPL license

email.load <- function(f) {
  singleString <- paste(readLines(f), collapse="\n")
  raw <-strsplit(singleString, "\n|\r")
  raw<-raw[[1]]

  return(raw)  
}

email.header <- function(raw) {
  headerEnd <- which(raw == "")[1]
  header <- raw[seq(1,headerEnd - 1)]
  return (header)
} 

email.body <- function(raw) {
  header <- email.header(raw)
  headerEnd <- length(header)
  body <- raw[seq(headerEnd + 2, length(raw) - 1)]
  
  headerCount <- which(substring(body, 0, 5) == "From:")
  if (length(headerCount) > 0) {
    nextHeaderStart <- headerCount[1]
    body <- body[seq(1,nextHeaderStart - 1)]
  }

  return (body)  
}

# execute stuff
#Sys.setlocale('LC_ALL','C') 
setwd('/home/cmack/Homebrew/Email')
#f<-'Example/REExample.txt'
f<-'Example/Example.txt'
raw <- email.load(f)
header <- email.header(raw)
body <- email.body(raw)

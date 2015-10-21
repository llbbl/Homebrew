#
# This is an attempt to port PlancakeEmailParser.php to R
# @url https://github.com/plancake/official-library-php-email-parser/find/master
# Similarly, this should have a GPL license

email.load <- function(f) {
  singleString <- paste(readLines(f), collapse="\n")
  raw <-strsplit(singleString, "\n|\r")
  #print(raw)
  
  header <- c();
  body <- c()
  
  startBody <- FALSE;
  
  for(i in 1:length(raw))
  {
    v <- raw[i]
    print(v)
    if (v == "" && startBody == FALSE ) {
      startBody <- TRUE
    } 
    
    if (startBody == FALSE) {
      header <- append(header, c(v))
    }
    else {
      body <- append(body, c(v)) 
    }
  }
  
  print(header)
}


# execute stuff
Sys.setlocale('LC_ALL','C') 
setwd('/home/cmack/Homebrew/Email')
email.load('Example/Example.txt')



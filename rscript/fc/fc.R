fc <- function(f1, f2){
  
  # lamba function used in this function
  append.list <- function(l,a) {
    l[length(l)+1]<-a
    return (l)
  }
  
  diffs <- list();
  
  # read in the two files
  l1 <- readLines(f1)
  l2 <- readLines(f2)

  largerFile <- l1;
  smallerFile <- l2;
  largerFN <- f1;
  smallerFN <- f2;
  
  if (length(l1) < length(l2)) {
    largerFile <- l2;
    smallerFile <- l1;
    largerFN <- f2;
    smallerFN <- f1;
  }
  countDiffs <- 0;
  countSame <- 0;
  
  tempChanges <- list();
  tempSecondaryChanges <- list();
  completeSmallerFile = FALSE
  
  for(i in 1:length(largerFile)) {
    # compare files
    if (i<=length(smallerFile)) {
      
      # if they are not equal start tracking
      if (largerFile[i] != smallerFile[i]) {
       
         countDiffs <- countDiffs + 1;
        
        # if this is the starting line of changes, add headers
        if (length(tempChanges) < 1) {
          tempChanges <-append.list (tempChanges, "************************")
          tempChanges <-append.list (tempChanges, paste("Starting Line:", i))
          tempChanges <-append.list (tempChanges, paste0("****", largerFN))
          tempSecondaryChanges <-append.list (tempSecondaryChanges, paste0("****", smallerFN))
        }
    
        # append changes
        tempChanges <-append.list (tempChanges, largerFile[i])
        tempSecondaryChanges <-append.list (tempSecondaryChanges, smallerFile[i])
      } 
      else {
        # files have a line that is the same
        # if we have changes already, write the sections to the diff list
        if (length(tempChanges) > 0) {
          for(j in 1:length(tempChanges)) {
            diffs <- append.list(diffs, tempChanges[j])
          }
          for(k in 1:length(tempSecondaryChanges)) {
            diffs <- append.list(diffs, tempSecondaryChanges[k])
          }
        }
        
        # clear list and start tracking again
        tempChanges <- list();
        tempSecondaryChanges <- list();
        
        countSame <- countSame + 1;
      }
    }
    else {
      # check this first line in larger file that is not in smaller file 
      # and there were changes that we were tracking
      if (length(tempChanges) > 0 && completeSmallerFile == FALSE) {
        for(j in 1:length(tempChanges)) {
          diffs <- append.list(diffs, tempChanges[j])
        }
        for(k in 1:length(tempSecondaryChanges)) {
          diffs <- append.list(diffs, tempSecondaryChanges[k])
        }
        
        # clear the list
        tempChanges <- list();
        tempSecondaryChanges <- list();
      }
      
      completeSmallerFile = TRUE;
      countDiffs <- countDiffs + 1;
      
      # if this is the first line after the smaller file ends, add a header
      if (length(tempChanges) < 1) {
        tempChanges <-append.list (tempChanges, "+++++++++++++++++++++++")
        tempChanges <-append.list (tempChanges, paste("Starting Line:", i))
        tempChanges <-append.list (tempChanges, paste0("++++", largerFN))
      }
      
      # list change
      tempChanges <-append.list (tempChanges, largerFile[i])
      
      # this is the last line in larger file, write to the diff list
      if (i == length(largerFile)) {
        j <- 0 # unnecessary? 
        for(j in 1:length(tempChanges)) {
          diffs <- append.list(diffs, tempChanges[j])
        }
      }
    }
  }
  
  diffs <-append.list (diffs, "======================= Summary")
  diffs <-append.list (diffs, paste("Num of Different Lines:", countDiffs))
  diffs <-append.list (diffs, paste("Num of Same Lines:", countSame))
  
  return (diffs)
}

setwd("/home/cmack/Homebrew/rscript/fc")
d <- fc("file2.txt", "file1.txt")
d <- as.matrix(d)
colnames(d) <- c("Differences Below")
write.csv(d, file="diff.txt", row.names=FALSE)


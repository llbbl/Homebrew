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
    
    if (i<=length(smallerFile)) {
      
      if (largerFile[i] != smallerFile[i]) {
       
         countDiffs <- countDiffs + 1;
        
        if (length(tempChanges) < 1) {
          tempChanges <-append.list (tempChanges, "************************")
          tempChanges <-append.list (tempChanges, paste("Starting Line:", i))
          tempChanges <-append.list (tempChanges, paste0("****", largerFN))
          tempSecondaryChanges <-append.list (tempSecondaryChanges, paste0("****", smallerFN))
        }
    
        tempChanges <-append.list (tempChanges, largerFile[i])
        tempSecondaryChanges <-append.list (tempSecondaryChanges, smallerFile[i])
      } 
      else {
        if (length(tempChanges) > 0) {
          for(j in 1:length(tempChanges)) {
            diffs <- append.list(diffs, tempChanges[j])
          }
          for(k in 1:length(tempSecondaryChanges)) {
            diffs <- append.list(diffs, tempSecondaryChanges[k])
          }
        }
        
        tempChanges <- list();
        tempSecondaryChanges <- list();
        
        countSame <- countSame + 1;
      }
    }
    else {
      if (length(tempChanges) > 0 && completeSmallerFile == FALSE) {
        # first line in larger file that is not in smaller file
        for(j in 1:length(tempChanges)) {
          diffs <- append.list(diffs, tempChanges[j])
        }
        for(k in 1:length(tempSecondaryChanges)) {
          diffs <- append.list(diffs, tempSecondaryChanges[k])
        }
        
        tempChanges <- list();
        tempSecondaryChanges <- list();
      }
      
      completeSmallerFile = TRUE;
      countDiffs <- countDiffs + 1;
      
      if (length(tempChanges) < 1) {
        tempChanges <-append.list (tempChanges, "+++++++++++++++++++++++")
        tempChanges <-append.list (tempChanges, paste("Starting Line:", i))
        tempChanges <-append.list (tempChanges, paste0("++++", largerFN))
      }
      
      tempChanges <-append.list (tempChanges, largerFile[i])
      
      if (i == length(largerFile)) {
        j <- 0
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


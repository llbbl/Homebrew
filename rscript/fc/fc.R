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
  
  for(i in 1:length(largerFile)) {
    if (i<=length(smallerFile)) {
      if (largerFile[i] != smallerFile[i]) {
        countDiffs <- countDiffs + 1;
        diffs <-append.list (diffs, "************************")
        diffs <-append.list (diffs, paste("Line:", i))
        diffs <-append.list (diffs, paste0("****", largerFN))
        diffs <-append.list (diffs, largerFile[i])
        diffs <-append.list (diffs, paste0("****", smallerFN))
        diffs <-append.list (diffs, smallerFile[i])
      }
      else {
        countSame <- countSame + 1;
      }
    }
    else {
      countDiffs <- countDiffs + 1;
      
      diffs <-append.list (diffs, "+++++++++++++++++++++++")
      diffs <-append.list (diffs, paste("Line:", i))
      diffs <-append.list (diffs, paste0("++++", largerFN))
      diffs <-append.list (diffs, largerFile[i])
    }
  }
  
  diffs <-append.list (diffs, "======================= Summary")
  diffs <-append.list (diffs, paste("Num of Different Lines:", countDiffs))
  diffs <-append.list (diffs, paste("Num of Same Lines:", countSame))
  
  return (diffs)
}

setwd("/home/cmack/Code/fc")
d <- fc("file1.txt", "file2.txt")
d <- as.matrix(d)
colnames(d) <- c("Differences Below")
write.csv(d, file="diff.txt", row.names=FALSE)


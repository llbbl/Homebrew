library(formatR)

ls("package:formatR")

p <- "/home/cmack/SneezyT/r/mysql_connect.R"
f <- file.path(p)
tidy_source(f, file=file(p))

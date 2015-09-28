install.packages("base64enc")
install.packages("httpuv")
install.packages("XML")

library(httr)
library(httpuv)
library(base64enc)
library(XML)

yahoo <- oauth_endpoint("get_request_token", "request_auth", "get_token",
                        base_url = "https://api.login.yahoo.com/oauth/v2")

id <- scan(file="/home/cmack/yahoo_client_id.txt", what=character())
secret <- scan(file="/home/cmack/yahoo_client_secret.txt", what=character())

myapp <- oauth_app("yahoo", key = id, secret=secret)

# 3. Get OAuth credentials
token <- oauth1.0_token(yahoo, myapp)

# 4. Generate signature and make requests
sig <- sign_oauth1.0(myapp, token$oauth_token, token$oauth_token_secret)

x<-GET("http://fantasysports.yahooapis.com/fantasy/v2/users;use_login=1/games;game_keys=nfl/leagues", sig)
content(x, "parsed")
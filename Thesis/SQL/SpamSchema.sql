/*
	Spam database for Charles Mack's Naive Bayes Classifier
*/
CREATE TABLE Spam
(
	ArticleId		INT IDENTITY,
	Title			Varchar(255),
	Category		Varchar(255),
	Body			Text
)

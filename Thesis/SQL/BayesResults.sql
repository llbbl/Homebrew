/*
	Result database for Charles Mack's Naive Bayes Classifier
*/
CREATE TABLE Run
(
	RunId INT IDENTITY,
	MasterStart		DateTime,
	MasterEnd		DateTime,
	FindWordsStart	DateTime NULL,
	FindWordsEnd	DateTime NULL,
	TrainStart		DateTime NULL,
	TrainEnd		DateTime NULL,
	TestStart		DateTime NULL,
	TestEnd			DateTime NULL
)

CREATE TABLE Configuration
(
	ConfigurationId INT IDENTITY,
	RunId INT,
	Name VARCHAR(200) NULL,
	Value VARCHAR(200) NULL
)

CREATE TABLE Summary
(
	SummaryId INT IDENTITY,
	RunId INT,
	Total INT,
	TotalCorrect INT,
	PercentCorrect DECIMAL,
	TotalIncorrect INT,
	PercentIncorrect DECIMAL
)

CREATE TABLE CategorySummary
(
	CategorySummaryId INT IDENTITY,
	RunId INT,
	TimesInTraining INT DEFAULT 0,
	CategoryName VARCHAR(200),
	CategoryTotal INT,
	CategoryCorrect INT,
	CategoryPercentCorrect DECIMAL,
	CategoryIncorrect INT,
	CategoryPercentIncorrect DECIMAL,
)


CREATE TABLE CategoryIncorrect
(
	CategoryIncorrectId INT IDENTITY,
	CategorySummaryId INT,
	RunId INT,
	KnownCategory VARCHAR(200),
	ResultCategory VARCHAR(200),
	ResultIncorrect INT
)
LOAD CSV WITH HEADERS FROM "file:///exams.csv" AS row
fieldterminator ";"
CREATE (n:Exam)
SET n = row,
n.duration = toInt(row.duration),
n.maxScore = toFloat(row.maxScore);

LOAD CSV WITH HEADERS FROM "file:///subjects.csv" AS row
fieldterminator ";"
CREATE (n:Subject)
SET n = row,
n.oralExam = toBoolean(row.oralExam);

LOAD CSV WITH HEADERS FROM "file:///teachers.csv" AS row
fieldterminator ";"
CREATE (n:Teacher:User)
SET n = row;

LOAD CSV WITH HEADERS FROM "file:///students.csv" AS row
fieldterminator ";"
CREATE (n:Student:User)
SET n = row;

//Kreiranje indeksa za brže pretraživanje.

CREATE INDEX ON :User(userID);

CREATE INDEX ON :Subject(subjectID);

CREATE INDEX ON :Exam(examID);


//Kreiranje veza.

LOAD CSV WITH HEADERS FROM "file:///enrolled_in.csv" AS row
fieldterminator ";"
MATCH (k:Subject), (s:Student)
WHERE k.subjectID = row.subjectID AND s.jmbag = row.jmbag
CREATE (s)-[v:ENROLLED_IN]->(k)
SET v.timesEnrolled = toInteger(row.timesEnrolled),
v.grade = toInteger(row.grade);

LOAD CSV WITH HEADERS FROM "file:///in.csv" AS row
fieldterminator ";"
MATCH (e:Exam), (s:Subject)
WHERE e.examID = row.examID AND s.subjectID = row.subjectID
CREATE (e)-[f:IN]->(s);

LOAD CSV WITH HEADERS FROM "file:///teaches.csv" AS row
fieldterminator ";"
MATCH (p:Teacher), (s:Subject)
WHERE p.oib = row.oib AND s.subjectID = row.subjectID
CREATE (p)-[f:TEACHES]->(s)
SET f = row;

LOAD CSV WITH HEADERS FROM "file:///takes.csv" AS row
fieldterminator ";"
MATCH (e:Exam), (s:Student)
WHERE e.examID = row.examID AND s.jmbag = row.jmbag
CREATE (e)<-[v:TAKES]-(s)
SET v = row,
v.passed = toBoolean(row.passed),
v.score = toFloat(row.score),
v.grade = toInteger(row.grade);

LOAD CSV WITH HEADERS FROM "file:///registered.csv" AS row
fieldterminator ";"
MATCH (e:Exam), (s:Student)
WHERE e.examID = row.examID AND s.jmbag = row.jmbag
CREATE (e)<-[v:REGISTERED_FOR]-(s)
SET v = row;

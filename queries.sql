

#How many people from each invitation are coming, for all invitations that have RSVP'd

SELECT i.name, SUM( g.coming ), i.special_message
FROM guest AS g, invitation AS i
WHERE g.invitation_id = i.id
AND i.responded =1
GROUP BY i.id

 

#View when each household RSVP'd

SELECT i.name, e.date, e.description, i.special_message
FROM invitation AS i, in_event AS e
WHERE e.invitation = i.id
ORDER BY date

 

#View individual guests who have responded, and whether they are coming or not.  Sorted by whether they are coming

SELECT g.first_name, g.last_name, g.submitted_name, g.coming
FROM invitation AS i, guest AS g
WHERE i.id = g.invitation_id
AND i.responded =1
ORDER BY g.coming

 

#View individual guests who have responded, and whether they are coming or not.  Grouped by invitation

SELECT i.id, g.first_name, g.last_name, g.submitted_name, g.coming
FROM invitation AS i, guest AS g
WHERE i.id = g.invitation_id
AND i.responded =1
ORDER BY i.id

 

#View individual guests who have responded, and aren't coming

SELECT g.first_name, g.last_name, g.submitted_name

FROM invitation AS i, guest AS g

WHERE i.id = g.invitation_id

AND i.responded =1

AND g.coming = 0

 

#View individual guests who have responded, and ARE coming

SELECT g.first_name, g.last_name, g.submitted_name

FROM invitation AS i, guest AS g

WHERE i.id = g.invitation_id

AND i.responded =1

AND g.coming = 1

 

#Total number of guests coming

SELECT SUM( coming )
FROM guest
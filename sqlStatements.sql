=== Login: ===
	--variables needed - $email,$password
	--The following query returns one or zero rows
	Select password 
	from (Users as U inner join RegisteredUsers as R on U.UID = R.UID)
	where email = $email

	stuff = parse(run(query))
	if stuff:
		if $password == stuff:
			LOGIN SUCCEEDED
		else:
			LOGIN FAILED
	else:
		LOGIN FAILED

== Signup: ==
	--variables needed - $username,$email,$password - username can be a blank string
	--The following query returns one or zero rows
	Select email
	from RegisteredUsers
	where email = $email

	stuff = parse(run(query))
	if stuff:
		USERNAME TAKEN
	else:
		INSERT INTO RegisterdUsers blah blah blah

== View Local Questions: ==
	--variables needed - $UID
	Select * from Questions 
	where (UID = $UID and location like %sessionGeo%) --a little more logic needed here in the like clause

== View Your Answers: ==
	--variables needed - $UID
	Select * from Answers where UID = $UID

== Rate Answers: ==
	--variables needed - $UID 
	Select AID from Rated where UID = $UID

== View self profile: ==
	--variables needed- $UID
	Select * from RegisteredUsers where UID = $UID

== View other profile: ==
	--variables needed - $otherUID
	Select * from RegisteredUsers where UID = $otherUID

== Set/Change Username: ==
	--LITERALLY SO EASY
	--variables needed - $NEWusername
	Update RegisteredUsers set username = $NEWusername

== Add Question: == 

== Add Answer: ==


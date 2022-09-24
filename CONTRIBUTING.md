## Getting started

### Issues

Before you open any issue, please, firstly make sure, that you are on latest version.
If you are using the latest version, search opened issues. There might be already
reported the problem, and we are currently working to solve it.  
In case you are not on latest version, check 
[CHANGELOG.md](https://gitlab.feo.cz/core/ship-on-laravel/-/blob/master/CHANGELOG.md),
that may list your problem as solved within a later version. If it is solved there,
you have no other choice but to update the library.

#### Create a new issue

If you have not found the problem you would like to report within opened issues,
nor in CHANGELOG, open new ticket.  
The ticket MUST have a **short** (max. 100 characters), although understandable and
**obvious title** of the problem. An example might be `Validation of the user failed`.  
**Description** starts with `found@x.x.x` tag on its own line, where `x.x.x` stands 
for full version, where the problem has been found (the one you are using). *Please,
note that if the problem has been found within not supported version, the problem will
not be fixed for your version.*  
After the tag, start a new line, to describe the whole problem. **How** the problem
happens, and **when** it happens should be mentioned, so we have as much information
as possible.  
Once you are done, save the ticket, **create a Merge Request**, and assign the ticket 
on Project Manager, or the Maintainer if there is no PM. The two persons can be found
at [README.md](https://gitlab.feo.cz/core/ship-on-laravel/-/blob/master/README.md) file.  

#### Create a Merge Request (MR)  

When you have created new ticket, reporting an issue, you should create a new MR.  
The name of MR is copied title of relevant issue, although it contains a prefix of
`#x - `, where `x` stands for number of the relevant ticket. Example: 
`#1 - Validation of the user failed`. When the MR is created, checkout its branch,
and prepare PHPUnit Test, within `/tests/Issues/xxxTest.php`, where `xxx` stands for
the MR's ticket. Again, an example can be `/tests/Issues/1Test.php`.   
We expect no other changes within the MR made by you. Once the ticket is solved, and
the fix goes through the test written by you successfully, there must not be any
other comments within the ticket, complaining about something else's not working out.  

#### After the MR  

When the MR is merged, the ticket is marked as `[SOLVED]`, closed and marked as 
`[CLOSED]`, and the ticket is assigned to you. When you are happy (to let you
know, it is done). You are free to revoke the assignment and assign nobody.  



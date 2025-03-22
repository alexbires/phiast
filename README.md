# phiast
Open source IAST for PHP.  Ever been looking at PHP source code wishing you had some way to see what values that variables had? Ever wish  you had a way to easily see where your HTTP variables end up?  Maybe due to things like Dependency Injection it's not always easy to statically trace routes?  Maybe there's this function that  you want to know what values get passed in that has an obvious injection, you just don't know how to get to it?  Maybe projects get really huge when they maybe shouldn't?  

This tool aims to add a free tool to the mix where we can easily blow through PHP code and try to uncover bugs quicker. 


## Methodology

I want to install xdebug in the container that the php application is running in.  I want to then output the php logs to a file location that is mounted in the container so the host can access it.  From there i can have a program host side process the php traces.  

Can start with just function level inputs.  Need a ui so maybe adding a feature in vscode to see variable values or (hopefully not) a web viewer. 

Want to have a feature where you can query if a breakpoint got hit based on the http request (and session variables).  Would need to store the http request in a database (or data store) of some sortt.

In order to make an easier time of instrumenting code we can run a semgrep scan with a configurable rule set to only tag things like rce/sql injection that sort of thing. 

Having the ability to receive an xdebug shell wouldn't be a bad thing need to research this. 


### Install Xdebug

We want to be able to install xdebug to aid us in this process. 

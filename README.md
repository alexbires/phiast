# phiast
Open source IAST for PHP.  Ever been looking at PHP source code wishing you had some way to see what values that variables had? Ever wish  you had a way to easily see where your HTTP variables end up?  Maybe due to things like Dependency Injection it's not always easy to statically trace routes?  Maybe there's this function that  you want to know what values get passed in that has an obvious injection, you just don't know how to get to it?  Maybe projects get really huge when they maybe shouldn't?  

This tool aims to add a free tool to the mix where we can easily blow through PHP code and try to uncover bugs quicker. 


## Methodology


### Install Xdebug

We want to be able to install xdebug to aid us in this process. 

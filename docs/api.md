# API Docs

This document will explain all of the APIs that we are using for this project. 

## /searchTraces
* Searches for a trace.
* Input:
* trace_id: string: the trace id (guid) that the trace is
* variables: array: an array of variables 
	* type: enum: get/post/http header/cookie/*: where the variable is
	* name: str: the name of the variable
	* value: str: the value of the variable


## /viewTrace
* Returns the individual trace.
* 


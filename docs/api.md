# API Docs

This document will explain all of the APIs that we are using for this project. 

## /searchTraces
* Searches for a trace.
* Inputs:
	* trace_id: str: the trace id (guid) that the trace is
	* variables: array: an array of variables 
		* type: enum: get/post/http header/cookie/*: where the variable is
		* name: str: the name of the variable
		* value: str: the value of the variable
	* output:
	``` {
		trace_ids: ['trace_id1', 'trace_id2']
	}
	```

## /viewTrace
* Returns the individual trace.
* Inputs:
	* trace_id: str: the trace id to pull back a trace from
	* pagination_start: int: the step to start the trace pagination from
	* pagination_end: int: the step to stop the trace pagingation from
		* If neither are given it will default to 0-50
* Output:
``` 
{

	trace_id: "trace_id1",
	trace_statements: [ 
	{
		line_no: <line number of trace>,
		ret_value: <return value>,
		step_no: the step number from the trace file,
		full_filename: "the full path to the file starting from function root"
	}
	]
	pagination_start: <start number>,
	pagination_end: <end number>
} 
```


## /getCode
* Returns the file contents for a given file.  Used primarily for the UI to display trace info
* Inputs:
	* file: str: The full path to a file.
* Output:
``` 
{ 

	}```
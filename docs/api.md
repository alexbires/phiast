# API Docs

This document will explain all of the APIs that we are using for this project. 

## /searchTraces
* Searches for a trace.
* Inputs:
	* traceId: str: the trace id (guid) that the trace is
	* variables: array: an array of variables 
		* type: enum: get/post/http header/cookie/*: where the variable is
		* name: str: the name of the variable
		* value: str: the value of the variable
* output:
	``` 
	{
		"traceIds": ['trace_id1', 'trace_id2']
	}
	```

## /viewTrace
* Returns the individual trace.
* Inputs:
	* traceId: str: the trace id to pull back a trace from
	* paginationStart: int: the step to start the trace pagination from
	* paginationEnd: int: the step to stop the trace pagingation from
		* If neither are given it will default to 0-50
* Output:
``` 
{

	"trace_id": "trace_id1",
	"trace_statements": [ 
	{
		"lineNo": <line number of trace>,
		"retValue": <return value>,
		"stepNo": the step number from the trace file,
		"filename": "the full path to the file starting from function root"
	}
	]
	"paginationStart": <start number>,
	"paginationEnd": <end number>
} 
```


## /getCode
* Returns the file contents for a given file.  Used primarily for the UI to display trace info
* Inputs:
	* file: str: The full path to a file.
* Output:
``` 
{ 
	"data": <b64 encoded zip file>
}
```

## /viewFiles
* Outputs a directory listing.  Used for the UI.
* Inputs:
	* N/A
* Outputs:
```
{
	"files":["full/path/to/file1.php", "full/path/to/file2.php"]
}

```

## /retrieveSQLStatements
* Retrieves a list of SQL Statements given a trace
* Inputs:
	* traceId: str: the trace id to pull back a trace from
* Outputs:
```
{
	"sql": [
		{
			"filename": "/path",
			"lineNo": 14,
			"statement": "select * from ....",
		}
	]
}

```


## /checkAuth
* Checks authentication credentials for the API.  As of now this will be a single user system that generates a 32 byte auth token when the server starts up.
* Inputs:
	* authToken: str: the authentication token to validate.
* Outputs:
```
{
	"auth":true/false
}
```

## /markFile
* Marks a file to have certain lines included in it for next run analysis.
* Input:
	* filePath: str: the full file path to check for.
	* lines: array: which lines to mark in the form [1,2,5-8]
* Output:
	* None
	
## /viewFileMarks
* Returns all marked files and line numbers.
* Input:
	* None
* Output:
```
{
	"marks": [
		{
			"path": "full path",
			"lines": [1,3,5-400],
		}
	]
}

```


## /traceEverything
* Determines whether we should just trace all files entirely.  Will be highly disk intensive.
* Inputs:
	* traceAll: bool: whether we should trace everything
* Outputs:
```
{
	"tracedAll": true/false
}
```

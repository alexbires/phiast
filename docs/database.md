# Database


This will will describe the database schema. 

Tables below:

Trace Metadata
| Column Name | Type | Description |
| trace_id | str | the id of the trace|
| trace_type | str | full or partial|

Trace Item table

| Column Name | Type | Description |
| ------ | ------ | ------ |
| trace_id | str | the id of the trace that we are looking at|
| filename | str | The full path to the file with the name|
| function_name | str | the name of the function being called|
| return_value | str | the return value|
| trace_type | str | full or partial trace|


Trace Input table (holds the inputs to a trace from an http perspective)

| Column Name | Type | Description |
| name | str | the name of the variable|
| value | str | the value of the variable|
| type | enum | the type of the variable|
| http_type | enum | the http type of the variable (e.g. GET param vs POST param) |
| trace_id | str | the id of the trace|




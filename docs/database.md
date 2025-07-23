# Database


This will will describe the database schema. 

Tables below:

Trace Metadata
- This keeps some metadata for the trace

| Column Name | Type | Description |
| ------ | ------ | ------ |
| trace_id | str | the id of the trace|
| trace_type | str | full or partial|

Trace Item table
- This keeps the individual lines of a trace

| Column Name | Type | Description |
| ------ | ------ | ------ |
| trace_id | str | the id of the trace|
| file_name | str | the full path to the file with the name|
| line_number | number | the number that the function is on|
| function_name | str | the name of the function being called|
| return_value | str | the return value|
| trace_type | str | full or partial trace|


Trace Input table
- This table holds the inputs to a trace from an http perspective

| Column Name | Type | Description |
| ------ | ------ | ------ |
| name | str | the name of the variable|
| value | str | the value of the variable|
| type | enum | the type of the variable|
| http_type | enum | the http type of the variable (e.g. GET param vs POST param)|
| trace_id | str | the id of the trace|


SQL Functions table
- This will keep a list of sql queries

| Column Name | Type | Description |
| ------ | ------ | ------ |
| trace_id | str | the id of the trace|
| sql_stmt | str | the entire sql statement|
| file_name | str | the full path to the file with the name|
| line_number | number | the number that the sql statement is on|
| trace_id | str | the id of the trace|

Regex Table
- This will keep a list of all regexes and the input passed to them

| Column Name | Type | Description |
| ------ | ------ | ------ |
| regex | str | The regex that we are matching to|
| input | str | The string the regex will operate on|
| file_name | str | the full path to the file with the name|
| line_number | number | the number that the sql statement is on|


Trace Tagging Table
- This will keep a list of all of the functions we want to trace if not using full trace mode

| Column Name | Type | Description |
| ------ | ------ | ------ |
| file_name | str | the full path to the file with the name|
| line_numbers_str | str | the line numbers to trace in format 1,4,6-49|

Trace All Table
- This will keep track of if we want to trace everything

| Column Name | Type | Description |
| ------ | ------ | ------ |
| trace_all | bool | should we trace everything|
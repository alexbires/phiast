package parser

import (
	"fmt"
	"strings"
)

type Record int

const (
	Entry Record = 0
	Return
	Exit
)

type XDebugLine struct {
	Kind Record

	Entry  *EntryLine
	Exit   *ExitLine
	Return *ReturnRecord
}

type EntryLine struct {
	level             int16 //the level of execution
	funcNo            int16 // the number of the function we are calling
	timeIndex       float64 //the time index of where we are executing
	memUsage         uint64 // how much memory we are using
	funcName         string // name of the function
	uoi               uint8 // user defined (1) or internal (0) function
	fileName         string // name of the file we are pulling from
	lineNumber       uint16 // the line number
	argCount         uint16 // the number of argumetns the function takes in
	arguments	[]string    //the arguments we have passed into the function
}

type ExitLine struct {
	level             int16 //the level of execution
	funcNo            int16 // the number of the function we are calling
	timeIndex       float64 //the time index of where we are executing
	memUsage         uint64 // how much memory we are using
}

type ReturnRecord struct {
	level             int16 //the level of execution
	funcNo            int16 // the number of the function we are calling
	retValue         string // the return value
}


// Returns an array of log lines for the given
func Parse(logs string) []XDebugLine{
	log_lines := strings.Split(logs, "\n")
	for i:=0; i<len(log_lines); i++ {
		
	}
	fmt.Printf(log_lines[0]);
}

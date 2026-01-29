package parser

import (
	"errors"
	"strconv"
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
	level      uint64   //the level of execution
	funcNo     uint64   // the number of the function we are calling
	timeIndex  float64  //the time index of where we are executing
	memUsage   uint64   // how much memory we are using
	funcName   string   // name of the function
	uoi        uint8    // user defined (1) or internal (0) function
	fileName   string   // name of the file we are pulling from
	lineNumber uint64   // the line number
	argCount   uint64   // the number of argumetns the function takes in
	arguments  []string //the arguments we have passed into the function
}

type ExitLine struct {
	level     int16   //the level of execution
	funcNo    int16   // the number of the function we are calling
	timeIndex float64 //the time index of where we are executing
	memUsage  uint64  // how much memory we are using
}

type ReturnRecord struct {
	level    uint16 //the level of execution
	funcNo   uint16 // the number of the function we are calling
	retValue string // the return value
}

// Returns an array of log lines for the given trace
func Parse(logs string) ([]XDebugLine, error) {
	log_lines := strings.Split(logs, "\n")

	if len(log_lines) < 4 {
		return nil, errors.New("Not enough lines to process")
	}
	parsedLines := make([]XDebugLine, len(log_lines)-4)

	for i := 3; i < len(log_lines)-1; i++ {
		entries := strings.Split(log_lines[i], "\t")
		switch entries[2] {
		case "0":
			lvl, _ := strconv.ParseUint(entries[0], 10, 16)
			fnc, _ := strconv.ParseUint(entries[1], 10, 16)
			tme, _ := strconv.ParseFloat(entries[3], 64)
			mem, _ := strconv.ParseUint(entries[4], 10, 16)
			u, _ := strconv.ParseUint(entries[6], 10, 8)
			line, _ := strconv.ParseUint(entries[9], 10, 16)
			arg, _ := strconv.ParseUint(entries[10], 10, 16)
			e := XDebugLine{
				Kind: Entry,
				Entry: &EntryLine{
					level:      lvl,
					funcNo:     fnc,
					timeIndex:  tme,
					memUsage:   mem,
					funcName:   entries[5],
					uoi:        uint8(u),
					fileName:   entries[8],
					lineNumber: line,
					argCount:   arg,
					arguments:  make([]string, arg),
				},
			}
			parsedLines[i-3] = e
		case "R":
			lvl, _ := strconv.ParseUint(entries[0], 10, 16)
			fnc, _ := strconv.ParseUint(entries[1], 10, 16)
			return_line := XDebugLine{
				Kind: Return,
				Return: &ReturnRecord{
					level:    uint16(lvl),
					funcNo:   uint16(fnc),
					retValue: entries[5],
				},
			}
			parsedLines[i-3] = return_line
		}
	}
	//fmt.Println(log_lines[0])
	return parsedLines, nil
}

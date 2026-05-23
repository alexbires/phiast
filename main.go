/**

Phiast is a PHP Interactive Application Security Testing tool

*/

package main

import (
	
	"github.com/phiast/pkg/parser"
)

var (
	traceFile = "Version: 3.4.2\nFile format: 4\nTRACE START [2025-08-19 21:12:27.842554]\n1	0	0	0.000520	360624	{main}	1		/var/www/html/happy.php	0	0"
)

func main() {
	parser.Parse(traceFile);
}


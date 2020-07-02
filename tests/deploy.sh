#!/bin/bash

# Create test-reports directory
mkdir -p ${WORKSPACE}/tests/test-reports

${WORKSPACE}/tests/tests.sh -j -o ${WORKSPACE}/tests/test-reports/report.xml

exit 0

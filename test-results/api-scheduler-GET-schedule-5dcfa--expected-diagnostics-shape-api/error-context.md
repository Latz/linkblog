# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: api/scheduler.spec.js >> GET /schedule/diagnostics >> returns the expected diagnostics shape
- Location: tests/e2e/api/scheduler.spec.js:150:9

# Error details

```
Error: apiRequestContext.get: connect ECONNREFUSED ::1:8888
Call log:
  - → GET http://localhost:8888/wp-json/linkdigest/v1/schedule/diagnostics
    - user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.7727.15 Safari/537.36
    - accept: */*
    - accept-encoding: gzip,deflate,br
    - Authorization: Basic YWRtaW46cGFzc3dvcmQ=

```
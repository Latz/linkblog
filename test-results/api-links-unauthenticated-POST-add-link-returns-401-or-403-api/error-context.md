# Instructions

- Following Playwright test failed.
- Explain why, be concise, respect Playwright best practices.
- Provide a snippet of code with the fix, if possible.

# Test info

- Name: api/links.spec.js >> unauthenticated POST /add-link returns 401 or 403
- Location: tests/e2e/api/links.spec.js:68:5

# Error details

```
Error: apiRequestContext.post: connect ECONNREFUSED ::1:8888
Call log:
  - → POST http://localhost:8888/wp-json/linkdigest/v1/add-link
    - user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.7727.15 Safari/537.36
    - accept: */*
    - accept-encoding: gzip,deflate,br
    - Authorization:
    - content-type: application/json
    - content-length: 47

```
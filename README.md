# PaparaX bank's security vulnerability

First, let's look at how security vulnerabilities occur and the types of vulnerabilities.

## Vulnerabilities

 A security vulnerability in a login page can arise when the application fails to properly handle user input or lacks appropriate security controls. Login pages are critical components of web applications, as they control access to user accounts and sensitive information. If not properly secured, attackers can exploit them to gain unauthorized access, steal data, or compromise the entire system.

Here are some common types of vulnerabilities often found in login pages:

**1. SQL Injection (SQLi):**
SQL Injection occurs when user input is directly included in SQL queries without proper validation or escaping. For example, if a login form takes the username and password and uses them in a raw SQL query like:

``` SELECT * FROM users WHERE username = 'input' AND password = 'input';```
An attacker can enter a specially crafted input like:
``` ' OR '1'='1```

This could change the query to always return true, effectively bypassing authentication and logging the attacker in as the first user in the database. This can lead to unauthorized access, data leakage, or even complete database compromise.

**2. Cross-Site Scripting (XSS):**
XSS attacks happen when a web application includes user-generated input in the page content without proper sanitization or encoding. In the context of a login page, this might occur in error messages, input fields, or URLs. An attacker might inject malicious JavaScript code that executes in the browser of another user. This can be used to:

- Steal session cookies and hijack accounts

- Redirect users to malicious websites

- Display fake login forms (phishing)

   XSS in login pages is especially dangerous because it targets user credentials and trust in the website.

**3. Command Injection:**
Command Injection occurs when the application executes system-level commands using user input without validating or sanitizing it. For example, if a login process involves calling a shell command with input parameters (which is poor practice), an attacker might enter:

```; rm -rf /```

This can allow the attacker to run arbitrary commands on the server, which may lead to complete system takeover, data deletion, or malware installation.
Remote Code Execution (RCE) is a severe security vulnerability that allows an attacker to execute arbitrary code on a remote server or system, typically through a web application. This happens when user input is not properly validated or sanitized, and that input is passed to system-level functions like exec(), system(), or other command execution methods.

**4. RCE vulnerabilities:**
often arise when developers directly include user input in backend operations, such as command-line calls, script execution, or file handling, without proper input filtering. An attacker can exploit this to run their own commands on the server — which may include reading or deleting files, installing malware, stealing sensitive data, or even taking full control of the system.

🔥 Why RCE is Dangerous:
- Allows attackers to bypass authentication

- Can lead to complete server compromise

- Enables remote access for persistence or lateral movement

- Often leads to data leaks, ransomware attacks, or system shutdowns

🛡️ Prevention:
Never pass user input directly into command execution functions

- Use safe APIs and avoid shell calls whenever possible

- Apply strict input validation and allowlists

- Use proper permission management and isolation

**How to Prevent These Vulnerabilities:**
- Always validate and sanitize all user inputs, both on the client and server side.

- Use prepared statements or parameterized queries to prevent SQL Injection.

- Escape or encode output properly to protect against XSS.

- Avoid executing system commands with user input. If unavoidable, use safe APIs and strict input validation.

- Implement rate limiting, CAPTCHA, and account lockout mechanisms to prevent brute force attacks.

- Use HTTPS to encrypt data during transmission and protect credentials from being intercepted.

By following secure coding practices and regularly testing for vulnerabilities, developers can significantly reduce the risk of login page attacks and protect both users and systems from compromise.

### Now let's examine PaparaX Bank's database and some code snippets.

- We have two authorized entries created with an SQL query.
  <img title="a title" alt="Alt text" src="/images/user.png">

- However, several errors on the login screen lead to some security vulnerabilities.
 <img title="a title" alt="Alt text" src="/images/code_snip.png"> 

- These security vulnerabilities allow us to easily access the login screen. ``` SELECT * FROM users WHERE username = 'input' AND password = 'input';```,``` ' OR '1'='1```

 <img title="a title" alt="Alt text" src="/images/admin.png"> 

 - Now we're inside. Pay attention to the details section.
   
 <img title="a title" alt="Alt text" src="/images/transfers_detail.png"> 

 - If you click on the “Update current amount” button, you will be exposed to a separate security vulnerability.(RCE vulnerabilities)

<img title="a title" alt="Alt text" src="/images/rce.png">

- The lack of a specific file format is a serious problem.
<img title="a title" alt="Alt text" src="/images/vuln.png">

- This file can execute certain commands on the system.```   whoami && hostname && ipconfig && net user && net localgroup administrators && systeminfo```
 <img title="a title" alt="Alt text" src="/images/cal.png">

- Our system information and much more data is now in the hands of others.
<img title="a title" alt="Alt text" src="/images/hassas.png">

## Prevention of security breaches 
- The security vulnerability in the login panel has been prevented in this way.
<img title="a title" alt="Alt text" src="/images/secure login.png">

- login failed
<img title="a title" alt="Alt text" src="/images/secure admin.png">

- and you can now upload files in a specific format and with enhanced security.
<img title="a title" alt="Alt text" src="/images/rce secure.png">
- Security is now stronger. Of course, not 100%.
<img title="a title" alt="Alt text" src="/images/csvv.png">
  





























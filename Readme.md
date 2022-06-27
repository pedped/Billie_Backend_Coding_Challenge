Welcome to billie assignment!

This project use Symphony framework, Here is the instruction on how to use this project, please follow these steps

1) Copy this project to your local system or server and then assign a domain to it. In this documentation, we think the
   project domain is www.billie.com

2) Make a new database in MySQL and set the settings in DATABASE_URL parameter in .env file like this

<pre>"mysql://[mysqlUserName]:[mysqlPassword]@127.0.0.1:3306/[mysqlDatabaseName]?serverVersion=[mysqlVersiuon]"</pre>

Now, Run this command to add tables

<pre>php bin/console doctrine:migrations:migrate</pre>

3) Before generating admin user, please change ( or leave ) ADMIN_PASSWORD parameter in .env file. After that, please
   make a Post request to this URL to generate admin user

<pre>www.billie.com/user/generate_admin</pre>

this will create an admin user for you. the email for admin is "convertersoft@gmail.com"

4) You can add new user ( which is normal user, not admin ) by this URL

<pre>www.billie.com/user/add_user</pre>

Parameters:
<table style="width: auto">
   <tr>
      <th>
      Parameter Name
      </th>
      <th>
      Type
      </th>
   </tr>
   <tr>
      <td>
      first_name
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      last_name
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      password
      </td>
      <td>
      string
      </td>
   </tr>
</table>

5) Now, to make a requests for working with Invoices and Companies, user require a token, to make a token, Post to this
   URL

<pre>www.billie.com/user/generate_token</pre>

Parameters:
<table style="width: auto">
   <tr>
      <th>
      Parameter Name
      </th>
      <th>
      Type
      </th>
   </tr>
   <tr>
      <td>
      email
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      password
      </td>
      <td>
      string
      </td>
   </tr>
</table>

Important: after this, you have to pass <strong>auth_user_id</strong> and <strong>auth_user_token</strong> with every
request.

6) now, it is time to make a new company, to do this, run this URL in POST request

<pre>www.billie.com/company/add</pre>


Parameters:

<table style="width: auto">
   <tr>
      <th>
      Parameter Name
      </th>
      <th>
      Type
      </th>
   </tr>
   <tr>
      <td>
      name
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      address
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      phone_number
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      vat_number
      </td>
      <td>
      integer
      </td>
   </tr>
</table>

7) After company created, it is time to make a invoice for company, run this URL in POST request

<pre>www.billie.com/invoice/add</pre>

Parameters:
<table style="width: auto">
   <tr>
      <th>
      Parameter Name
      </th>
      <th>
      Type
      </th>
   </tr>
   <tr>
      <td>
      company_id
      </td>
      <td>
      integer
      </td>
   </tr>
   <tr>
      <td>
      title
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      summery
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      vat_number
      </td>
      <td>
      integer
      </td>
   </tr>
   <tr>
      <td>
      terms
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      line_items
      </td>
      <td>
     encoded json of LineItem[]
      </td>
   </tr>
   <tr>
      <td>
      currency
      </td>
      <td>
      string
      </td>
   </tr>
</table>

in the about command, the LineItems are array of this items
<table style="width: auto">
   <tr>
      <th>
      Parameter Name
      </th>
      <th>
      Type
      </th>
   </tr>
   <tr>
      <td>
      description
      </td>
      <td>
      string
      </td>
   </tr>
   <tr>
      <td>
      quantity
      </td>
      <td>
      integer
      </td>
   </tr>
   <tr>
      <td>
      unit_price
      </td>
      <td>
      float
      </td>
   </tr>
   <tr>
      <td>
      vat
      </td>
      <td>
      float
      </td>
   </tr>
</table>

8) It is time to make a invoice as payed, run this URL in POST request

<pre>www.billie.com/invoice/set_payed</pre>

Parameters
<table style="width: auto">
   <tr>
      <th>
      Parameter Name
      </th>
      <th>
      Type
      </th>
   </tr>
   <tr>
      <td>
      invoice_id
      </td>
      <td>
      integer
      </td>
   </tr>

</table>

there is lots of tests for this project, to tun tests, please execute this command in root path of package

<pre>php bin/phpunit</pre>


<h3>Some tips</h3>
<ul>
   <li>
      I have used general Exception for exceptions, but it is best practice to use custom exception for each error
   </li>

</ul>


<h3>Next Steps</h3>
<ul>
   <li>
     Add Validator to entities
   </li>
   <li>
      Add Mock tests
   </li>
</ul>



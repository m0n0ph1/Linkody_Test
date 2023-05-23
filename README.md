# Linkody_Test

# Test Description : 

We need a simple Symfony application that allows users to import URLs from a CSV file.
The application consist of just one form to select the CSV file. The URLs are inserted into a MySQL table. 

Before inserting the URLs, we want to check if they are already in the table so we don’t insert duplicates. After the form is submitted we inform the user how many URLs have been added to the table.

The table is stored in MySQL with the InnoDB engine, the Dynamic row format, and the utf8mb4 character set. With this setting, MySQL has an index key prefix length limit of 3072 bytes.

We have 3 additional requirements:

1/ The table can grow to millions of URLs. However, we can’t have the user wait more than a couple of seconds for inserting CSV files with tens of thousands of URLs. What are the usual approaches?

2/ The URLs can be up to 2048 characters. What problem are we facing? How to solve that problem?

3/ We want all versions of the same URL to match. For instance, if 2 URLs differ only by the scheme, they are considered the same URL. If one URL has the default port 80 and another has no port, they are considered the same URL. If the URLs have the same query parameters and values but in different orders, they are considered the same URL.
Note: you don’t have to implement all conditions of this constraint. Just show that you understand what is required.

Implement a mini Symfony application taking into consideration the 3 previous constraints.


# Problems

<h3>1. The table can grow to millions of URLs. However, we can’t have the user wait more than a couple of seconds for inserting CSV files with tens of thousands of URLs. What are the usual approaches?</h3>

An approach is to process the import in smaller batches, with each batch containing a limited number of URLs. This ensures that the user doesn't have to wait too long for the entire import to complete. The Symfony Console and Process components can be used to implement a batch processing solution.

<h3>2. The URLs can be up to 2048 characters. What problem are we facing? How to solve that problem?</h3>

For each line in the file, extract the URL and store it in an array. Once you have a batch of URLs (e.g., 1000 URLs), use Doctrine's EntityManager to persist them to the database. Clear the EntityManager after each batch to free up memory. Repeat steps 3-5 until all URLs have been imported. By implementing this approach in Symfony, we can ensure that large CSV files with long URLs can be imported without running into issues related to maximum input variable size limits.

<h3>3. We want all versions of the same URL to match. For instance, if 2 URLs differ only by the scheme, they are considered the same URL. If one URL has the default port 80 and another has no port, they are considered the same URL. If the URLs have the same query parameters and values but in different orders, they are considered the same URL.</h3>

In Symfony, for considering URL variations as the same one, we can use the canBeConsideredTheSame() method of the Url class from the Symfony\Component\HttpFoundation namespace. This method returns a boolean indicating whether two URLs can be considered the same or not, based on several criteria such as scheme, host, port, and query parameters.

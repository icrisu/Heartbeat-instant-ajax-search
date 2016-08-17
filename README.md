# Heartbeat - Instant Ajax Search for WP

<P>HeartBeat is a blazing-fast ajax search solution for WordPress, it can search through thousands of posts within a fraction of a second. You can add any post type to the search index, and yes you can also add WooCommerce products.
<P>[Demo](http://www.sakuraplugins.com/products-list/heartbeat-instant-ajax-search-for-wordpress/)
<P>[License](https://codecanyon.net/licenses/standard)

## Tech specs
<P>HeartBeat is so fast because it indexes your posts from the database ( post, pages and any other custom post type you choose: Ex: WooCommerce ).

<P>HeartBeat uses the HTML5 local storage feature to store the indexes on the client side where the search is performed using LunrJS (a full text search engine for client side applications which enables great search experience without the need for external services).

<P>Once a user has stored the indexed data, which is pretty small (about 40KB / one thousand posts), it does not retrieve it next time, it checks the hash of the local indexed data against the server last index hash and if it’s needed will only update the differences.

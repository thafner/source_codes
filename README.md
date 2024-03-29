# source_codes

This is a module that I wrote that works with the Redirect module.

## Intro
This was for a client that had something called "source codes" on their site.  Source codes were submitted with forms and allowed the client to track users in Salesforce.

Part of the source code requirements was the ability to "attach" a source code cookie to a user based on a redirect.

For example, `/sign-up-for-our-special-thanksgiving-event` (call this the incoming URL) needs to redirect to `/sign-up` (call this the destination URL) and add a source code in a cookie that will follow the user throughout the site.

## Problem
Client needs the ability to:
* Look up a source code based on the incoming URL and add it as a cookie
* Redirect to the destination URL 
## Solution
* Create a custom Source Code entity [SourceCodeRedirect](https://github.com/thafner/source_codes/blob/master/src/Entity/SourceCodeRedirect.php)
* Implement entity_refererence_autocomplete on Redirect entity types [entityReference](https://github.com/thafner/source_codes/blob/master/src/Entity/SourceCodeRedirect.php#L200)
   *  This allows us to look up a redirect to get the destination URL
* Create an EventSubscriber to add the cookie [EventSubscriber](https://github.com/thafner/source_codes/blob/master/src/EventSubscriber/SourceCodeRedirectRequestSubscriber.php)
   *  This is where we match Redirect and SourceCodeRedirect entities and create a cookie based on the results
   *  The most important part of this is that this all takes place **directly before** the redirect module EventSubscriber.

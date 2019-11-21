# source_codes

This is a module that I wrote that works with the Redirect module.

## Intro
This was for a client that had something called "source codes" on their site.  Source codes were submitted with forms and allowed the client to track users in Salesforce.

Part of the source code requirements was the ability to "attach" a source code to a redirect so they could use vanity URLs that redirected to certain pages.

For example, `/sign-up-for-our-special-thanksgiving-event` needs to redirect to `/sign-up` and add a source code in a cookie that will follow the user throughout the site.

## Problem
Client needs the ability to:
1. Create a custom Source Code entity [SourceCodeRedirect](https://github.com/thafner/source_codes/blob/master/src/Entity/SourceCodeRedirect.php)
2. 

## Solution

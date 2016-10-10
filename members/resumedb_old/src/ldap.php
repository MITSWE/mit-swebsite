<?php
/*
 * Written by Dan Cogswell
 * cogswell@mit.edu
 * 1/10/06
 *
 * This function will return an *ARRAY* containing the MIT LDAP search results
 * of the search string $field for athena user $username.  It may be useful
 * to run LDAP_printEntireEntry() to determine an appropriate $field string
 * On error, this function returns a null value.  Errors will most likely occur
 * because either the field does not exist for the user, or the username is not
 * valid.
 *
 * Useful values for the $field variable:
 *  'street' = can be used to determine living group
 *  'ou' = department
 *  'title' = can be used to differentiate students from professors
 */
//-----------------------------------------------------------------------------
function getLDAPfield($field,$username){
  $ds=ldap_connect("ldap.mit.edu");  
  $sr=ldap_search($ds, "dc=mit,dc=edu", "uid=$username");
  $entry=ldap_first_entry($ds,$sr); 

  //Only continue if an entry is returned
  if ($entry){

    //If $field exists as an attribute for the entry, return its value
    $attributes=ldap_get_attributes($ds,$entry);
    if (in_array($field,$attributes))
      $result=ldap_get_values($ds,$entry,$field);
  }
  
  ldap_close($ds);
  return($result);
}


//Use this function for debugging only
//When given a username, it will print the entire LDAP entry for that user 
//-----------------------------------------------------------------------------
function LDAP_printEntireEntry($username){
  $ds=ldap_connect("ldap.mit.edu");  

  if ($ds) {
    $sr=ldap_search($ds, "dc=mit,dc=edu", "uid=$username"); 

    //Since we're searching by username there should only be 1 entry
    //But enumerate everything in the hash anyway as an example
    for ($entry=ldap_first_entry($ds,$sr); 
         $entry!=false;
	 $entry=ldap_next_entry($ds,$entry)){
 
      //Do stuff with the entries
      $attrs=ldap_get_attributes($ds,$entry);
       echo "dn: ".ldap_get_dn($ds,$entry)."<br>";     
      for ($i=0; $i<$attrs["count"]; $i++){
        echo "$attrs[$i]: ";
        $values=ldap_get_values($ds,$entry,$attrs[$i]);
	for ($j=0; $j<$values["count"]; $j++){
	  echo "$values[$j] ";
	}
        echo "<br>";
      }
    }
    ldap_close($ds);
  }
}
?>

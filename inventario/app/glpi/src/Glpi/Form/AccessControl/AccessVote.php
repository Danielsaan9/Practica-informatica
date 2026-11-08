<?php

namespace Glpi\Form\AccessControl;

/**
 * ControlTypeInterface::canAnwser require to return an AccessVote.
 * Ideally, a control type will pick between two types depending on how it is
 * labelled to end users.
 *
 * Here is an example for an hypothetical control type that would manager access
 * according to the user IP.
 * The control type could word its label in 3 different ways and for each labels
 * there is a logical vote couple:
 * vote couples:
 * - "Allow access by specific IP": (Grant, Abstain)
 * - "Restrict access by specific IP": (Grant, Deny)
 * - "Block access to specific IP": (Abstain, Deny)
 */
enum AccessVote
{
    /**
      * Grant access.
      * The access grant can still be denied if any other policy vote is `Deny`.
      */
    case Grant;

    /**
      * Does not allow access, but does not deny it either.
      * Access grant will depend on other policies vote.
      */
    case Abstain;

    /**
      * Deny access.
      * The access grant will be refused, whatever the other policies vote is.
      */
    case Deny;
}

#!/usr/bin/env sh
# For sheckcheck ↑ only.

# Ubuntu default.

if [ "${BASH}" ]; then
  if [ -f ~/.bashrc ]; then
    . ~/.bashrc;
  fi;
fi;

# Customization.

. ~/.env-path;
. ~/.env-vars;

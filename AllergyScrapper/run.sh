#!/bin/bash
/bin/sleep `/usr/bin/expr $RANDOM % 600` ;
exec php /home/cmack/Homebrew/AllergyScrapper/runner.php

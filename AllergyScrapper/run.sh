#!/bin/bash
/bin/sleep `/usr/bin/expr $RANDOM % 600` ;
cd /home/cmack/Homebrew/AllergyScrapper
exec php /home/cmack/Homebrew/AllergyScrapper/runner.php


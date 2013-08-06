#!/bin/bash
if [ $(($RANDOM/32768)) -gt 25 ]; then exit; fi
exec php /home/cmack/Homebrew/AllergyScrapper/runner.php

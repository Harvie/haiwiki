# Harvie's (almost) universal PHP Makefile
# Performs basic operations on PHP Project
###########################################

VERSION=$(shell date -u +%F_%H-%M-%S)
PRODUCT=HaiWiki
AUTHOR=Harvie 2o1o ( http://blog.harvie.cz/ )

NODIST=cache|backup|dump

MINI=index.min.php
DUMPDIR=./dump

DISTNAME=$(PRODUCT)-$(VERSION)
DISTDIR=_$(PRODUCT)-dist
DISTARCHIVE=$(DISTDIR)/$(DISTNAME).tar.gz

TMPDIR=_tmp/
TMPDIST=$(TMPDIR)$(DISTNAME)

help:
	@clear
	####################################################################################
	#
	#   $(PRODUCT) Makefile ;o)
	#   v$(VERSION)
	#   by $(AUTHOR)
	#
	# == $(PRODUCT) ==
	#	make clean	: clear cache
	#	make purge	: clear cache, dump and backup files
	#	make dump	: create static html dump of wiki
	#
	# == Admin ==
	#	make install	: set permissions
	#	make update	: update wiki to new version (from FTP or GIT)
	#
	# == Developer ==
	#	make mini	: compile whole project to single php file
	#	make todo	: search project for upper-case 'todo' strings
	#	make dist	: pack wiki for distribution
	#	make publish	: upload new dist to FTP or GIT server (for make update)
	#	+ you can override version. eg.: make dist VERSION=0.1a
	#
	# == Documentation ==
	#	make help	: print this help
	#	make		: = make help
	#	make info	: print longer help
	#

info:
	@clear
	# TODO: Write Makefile documentation

install:
	sudo chown -R $(USER):http .
	sudo chmod -R g+rwx .

clean: install
	rm -rf cache/*

purge: install clean
	rm -rf {backup,dump}/*

update: clean
	# TODO: updating (downloading) not working yet ;(
	# wget $(URL)$(PACKAGE)
	# tar xvvzf $(PACKAGE)

dump: pages/*
	echo $(DUMPDIR)
	rm -rf $(DUMPDIR)
	mkdir -p $(DUMPDIR)
	DUMPDIR=$(DUMPDIR) php -r "\$$wiki_include=''; include('index.php'); wiki_dump(getenv('DUMPDIR'));" 2>/dev/null
	links -source $(DUMPDIR)/ > $(DUMPDIR)'/index.php'

todo:
	fgrep -R $(shell echo -ne '\x54\x4f\x44\x4f') .

$(MINI): index.php plugins/*
	php -w index.php > index.min.php
	# TODO: compile includes into $(MINI)

mini: $(MINI)

$(DISTARCHIVE): $(shell ls -A1 | egrep -v '^(_|($(NODIST))$$)')
	mkdir -p $(TMPDIST) $(DISTDIR)

	cp -r $? $(TMPDIST)
	#rm -r $(TMPDIST)/{cache,backup,dump}
	tar -C $(TMPDIR) -cvvzf $@ $(DISTNAME)
	rm -rf $(TMPDIR)

dist: $(DISTARCHIVE)

publish: dist
	# TODO: publishing (uploading) not working yet ;(
	# here you can upload $(DISTARCHIVE) on your public ftp server or etc...
	# so everybody will be able to download and install it using "make update"

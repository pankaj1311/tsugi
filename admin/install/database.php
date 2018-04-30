<?php

$DATABASE_UNINSTALL = array(
"drop table if exists {$CFG->dbprefix}lms_tools",
"drop table if exists {$CFG->dbprefix}lms_tools_status"
);

$DATABASE_INSTALL = array(
array( "{$CFG->dbprefix}lms_tools",
"create table {$CFG->dbprefix}lms_tools (
    tool_id             INTEGER NOT NULL AUTO_INCREMENT,
    toolpath            VARCHAR(128) NOT NULL UNIQUE,
    name                TEXT NOT NULL,
    description         TEXT NOT NULL,
    clone_url           TEXT NOT NULL,
    gitversion          VARCHAR(1024) NULL,
    git_user            VARCHAR(1024) NULL,
    git_password        VARCHAR(1024) NULL,

    rank                INTEGER NULL,
    deleted             TINYINT(1) NOT NULL DEFAULT 0,

    json                MEDIUMTEXT NULL,
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP NULL,
    deleted_at          TIMESTAMP NULL,

    PRIMARY KEY (tool_id)
 ) ENGINE = InnoDB DEFAULT CHARSET=utf8"),

array( "{$CFG->dbprefix}lms_tools_status",
"create table {$CFG->dbprefix}lms_tools_status (
    tool_id             INTEGER NOT NULL,
    ipaddr              VARCHAR(64),
    status_note         TEXT NULL,
    commit              TEXT NULL,
    commit_log          TEXT NULL,

    json                MEDIUMTEXT NULL,
    created_at          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP NULL,

    CONSTRAINT `{$CFG->dbprefix}lms_tools_status_ibfk_1`
        FOREIGN KEY (`tool_id`)
        REFERENCES `{$CFG->dbprefix}lms_tools` (`tool_id`)
        ON DELETE CASCADE ON UPDATE CASCADE,

    PRIMARY KEY(tool_id, ipaddr)
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);

$DATABASE_UPGRADE = function($oldversion) {
    global $CFG, $PDOX;

    if ( ! $PDOX->columnExists('commit', "{$CFG->dbprefix}lms_tools_status") ) {
        $sql= "ALTER TABLE {$CFG->dbprefix}lms_tools_status ADD commit TEXT NULL";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    if ( ! $PDOX->columnExists('commit_log', "{$CFG->dbprefix}lms_tools_status") ) {
        $sql= "ALTER TABLE {$CFG->dbprefix}lms_tools_status ADD commit_log TEXT NULL";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    if ( ! $PDOX->columnExists('git_user', "{$CFG->dbprefix}lms_tools_status") ) {
        $sql= "ALTER TABLE {$CFG->dbprefix}lms_tools_status ADD git_user VARCHAR(1024) NULL";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    if ( ! $PDOX->columnExists('git_password', "{$CFG->dbprefix}lms_tools_status") ) {
        $sql= "ALTER TABLE {$CFG->dbprefix}lms_tools_status ADD git_password VARCHAR(1024) NULL";
        echo("Upgrading: ".$sql."<br/>\n");
        error_log("Upgrading: ".$sql);
        $q = $PDOX->queryDie($sql);
    }

    return 201804301336;

}; // Don't forget the semicolon on anonymous functions :)


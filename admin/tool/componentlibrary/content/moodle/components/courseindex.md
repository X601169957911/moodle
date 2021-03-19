---
layout: docs
title: "Course index"
description: "The course index improves course navigation and editing."
date: 2021-04-14T06:06:06+01:00
draft: false
weight: 21
tags:
- In development
---

<style>
    div[data-template="core_course/courseindex"] .courseindex {
        max-width: 250px;
        border: 1px solid gray;
        padding: 10px;
    }
</style>
{{< mustache template="core_course/courseindex" show_markup="true">}}
{{< /mustache >}}
For this example the course index has been put in a 250px wide box. This is not part of the mustache template

## How it works

The course index provides a quick way to navigate a course page. It shows course sections and course activities, each section in
the course index can be collapsed or expanded separately.

**todo :** Users can reorder the course page dragging and dropping activities

The course index is rendered from a template in
**course/templates/courseindex.mustache**.

The course index can be rendered in a drawer or a block and contains

* Menu title
* Expand / collapse toggles
* Linked sections
* Linked activities

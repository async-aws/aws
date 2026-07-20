---
category: contribute
---

# Documentation

The documentation is hosted on [async-aws.com](https://async-aws.com/). The contents
are automatically updated from the contents of the [async-aws/aws](https://github.com/async-aws/aws)
repository.

The [build process](#the-process-of-building-the-website) is fairly automated and
doesn't require much thought. But it is interesting to understand how one actually
would improve the website.

## Writing documentation

Writing documentation for the website is no more complex than usual. Making
changes to the Markdown files in `./docs` is all one needs to do.

If a new file is created and should appear in the website's sidebar menu, remember
to update `./couscous.yml`.

Here are some guidelines that are helpful when writing good documentation:

- Write `AsyncAws` or `async-aws` to refer to the project.
- Don't use "we" at any cost.
- Try not to write "you".
- Define all variables to make examples executable.
- Use meaningful names of variables and values. Avoid "foobar" and other dummy values.
- Write examples with `use` statements and don't use `<?php`.
- Use input objects over arrays.
- Use short variable names for clients, e.g. `$lambda` over `$lambdaClient` and `$s3` over `$s3Client`.

## Improving the website's appearance

To update the website's HTML or styles, one needs to use Couscous. Its basic operations
are found in their [Getting started guide](http://couscous.io/docs/getting-started.html).
You also need npm installed to build the assets.

There is a Makefile script to run `npm install` and `encore prod`. It will be executed
with `make website-assets`. That script will automatically run before Couscous is
generating the HTML.

What you basically need to do is to open two terminals. In one you run

```shell
couscous preview
```

To make sure the assets update when you make a change to the asset source:

```shell
cd website
./node_modules/.bin/encore dev --watch
```

Now you are good to go.

## The process of building the website

So here is an overview how the automated build process works every time there is
a push to master:

### 1. Generate the HTML

The first step is to use [Couscous](http://couscous.io/) to convert all Markdown files
in `./docs` to HTML files. Couscous then looks at the Twig template in `./website/template`
to get the basic HTML layout. All markdown files will use the `default.twig` template
unless otherwise specified in the metadata section at the top of the Markdown file.

The sidebar menu structure is defined in `./couscous.yml`.

All frontend assets are built using Webpack Encore. The source files live in
`./website/assets`.

### 2. Process HTML files

After the HTML files are generated, Couscous will execute `make website-post-process`.
That will look at the generated HTML and make some changes to it. For example, it will
style all code examples with `highlight.js`.

### 3. Publishing the files

There is a GitHub Action that generates the HTML and pushes it to a
"read-only" repository for the website. That repository uses [Netlify](https://www.netlify.com/)
to deploy the contents to [async-aws/aws](https://github.com/async-aws/aws).

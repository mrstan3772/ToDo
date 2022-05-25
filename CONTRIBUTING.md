# Contributing

When contributing to this repository, please first discuss the change you wish to make via issue,
email, or any other method with the owners of this repository before making a change.

Please note we have a code of conduct, please follow it in all your interactions with the project.

## Issues

Issues is the ideal channel for bug reports, new features or to submit pull requests, however please note the following restrictions :

- Do not use this channel for your personal requests for help.
- It is forbidden to insult or offend in any way while commenting on an issue. Respect the opinions of others, and stay focused on the main discussion.

## Bug Report

A bug is a specific error, caused by the code in this repository.

Guideline :

- Make sure you don't create an already existing report, consider using the search system.
- Check if the bug is fixed, trying on the last version of the code on the production or develop branch.
- Isolating the problem allows to create a simple and identifiable test case.

## New Functionality

It' s always good to offer new features. However, take the time to think about it, make sure the feature fits the project's goals.

You have to make a convincing argument to show at developers how this feature will benefit to the project.

## Pull Request Process

1. Ensure any install or build dependencies are removed before the end of the layer when doing a
   build.
2. Update the README.md with details of changes to the interface, this includes new environment
   variables, exposed ports, useful file locations and container parameters.
3. Increase the version numbers in any examples files and the README.md to the new version that this
   Pull Request would represent. The versioning scheme we use is [SemVer](http://semver.org/).
4. You may merge the Pull Request in once you have the sign-off of two other developers, or if you
   do not have permission to do that, you may request the second reviewer to merge it for you.

A good pull request is a great help. They should stay within the scope of the project, not contain non-project related commits.

Please ask before you post your pull request, otherwise you risk losing time because the project team does not want to integrate your work.

Follow this process in order to submit a pull request that respects good practices:

- Install the project according to the Readme.md

- If you cloned the project some time ago, remember to get the latest changes:

```bash
git checkout main
git pull
```

- Create a new branch that will contain your feature, modification or correction:

For a new feature or modification :

```bash
git checkout dev
git checkout -b feature/<feature-name>
```

For a new fix:

```bash
git checkout main
git checkout -b hotfix/<feature-name>
```

- Test your changes to make sure they don't produce regression. Run the command compose :

```bash
composer run-script phpunit-start-test --dev
```

It will create the test base, add a bunch of data to it, then run the PhpUnit tests.
They all have to be "OK" before the commit.

- Commit your changes: please respect the naming convention of your commits as follows :

```bash
<type>: <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```

The header is required.

- Push your branch onto your repository:

```bash
git push origin <branch-name>
```

## Code of Conduct

### Our Pledge

In the interest of fostering an open and welcoming environment, we as
contributors and maintainers pledge to making participation in our project and
our community a harassment-free experience for everyone, regardless of age, body
size, disability, ethnicity, gender identity and expression, level of experience,
nationality, personal appearance, race, religion, or sexual identity and
orientation.

### Our Standards

Examples of behavior that contributes to creating a positive environment
include:

- Using welcoming and inclusive language
- Being respectful of differing viewpoints and experiences
- Gracefully accepting constructive criticism
- Focusing on what is best for the community
- Showing empathy towards other community members

Examples of unacceptable behavior by participants include:

- The use of sexualized language or imagery and unwelcome sexual attention or
  advances
- Trolling, insulting/derogatory comments, and personal or political attacks
- Public or private harassment
- Publishing others' private information, such as a physical or electronic
  address, without explicit permission
- Other conduct which could reasonably be considered inappropriate in a
  professional setting

### Our Responsibilities

Project maintainers are responsible for clarifying the standards of acceptable
behavior and are expected to take appropriate and fair corrective action in
response to any instances of unacceptable behavior.

Project maintainers have the right and responsibility to remove, edit, or
reject comments, commits, code, wiki edits, issues, and other contributions
that are not aligned to this Code of Conduct, or to ban temporarily or
permanently any contributor for other behaviors that they deem inappropriate,
threatening, offensive, or harmful.

### Scope

This Code of Conduct applies both within project spaces and in public spaces
when an individual is representing the project or its community. Examples of
representing a project or community include using an official project e-mail
address, posting via an official social media account, or acting as an appointed
representative at an online or offline event. Representation of a project may be
further defined and clarified by project maintainers.

### Enforcement

Instances of abusive, harassing, or otherwise unacceptable behavior may be
reported by contacting the project team at [INSERT EMAIL ADDRESS]. All
complaints will be reviewed and investigated and will result in a response that
is deemed necessary and appropriate to the circumstances. The project team is
obligated to maintain confidentiality with regard to the reporter of an incident.
Further details of specific enforcement policies may be posted separately.

Project maintainers who do not follow or enforce the Code of Conduct in good
faith may face temporary or permanent repercussions as determined by other
members of the project's leadership.

### Attribution

This Code of Conduct is adapted from the [Contributor Covenant][homepage], version 1.4,
available at [http://contributor-covenant.org/version/1/4][version]

[homepage]: http://contributor-covenant.org
[version]: http://contributor-covenant.org/version/1/4/

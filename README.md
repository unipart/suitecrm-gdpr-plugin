# Unipart Digital - SuiteCRM GDPR Plugin

The EU General Data Protection Regulation (GDPR) is a significant change in data privacy regulation. It enhances the rights of any person to have full control of their own data.

The **Unipart Group** is committed to complying with these regulations to the best of its ability, hence the reason for this module.

## Prerequisite GDPR knowledge 

For a company there are several steps to be done to achieve the full compliance with the GDPR requirements, we can group them in 3 main categories:

- Management level: all the requirements that involve management knowledge, often picked up by 3rd part consultant.
- Infrastructure level: Assessment of vulnerabilities and risks, management of data including encrypted partitions and backups, continuous monitoring etc.
- CRM level: management of the data being manipulated and ensuring that this manipulation is carried out within a secure and legal framework.

This plugin is designed to improve SuiteCRM compliance by providing an additional management tool to help with monitoring and enforcement of these requirements.  

## How it works

The plugin is composed by 5 SuiteCRM entities:

1. **Text Field Types**: Each SuiteCRM entity is a form composed by several fields of several types. Someone of them allow to write free text notes in which a SuiteCRM operator could wrongly write sensitive data. To avoid this situation we should monitor those free text fields and reviews the content. TFT allows you to choose which field needs to be monitored.
1. **Text Field Reviews**: If a field is monitored through TFT, each time an operator create or edit the content a new Text Field Review entity is created with the content of the text wrote, what entity / field it belongs and who is the author. In this way it is possible to review each free text field and this will allow us to grant that each operator is correctly fulfilling the GDPR's requirements. 
1. **Privacy Campaigns**:  The company needs to communicate with their customer for different reason. Is it in the rights of the customer to choose what type of communications wants to receives, to group this communication and allow the customer to give or withdraw the consent, the plugin allow you to create Privacy campaign that once created they will be selectable from the Privacy Preferences entity.
1. **Privacy Preferences**: Contacts, Leads and Accounts need to give their consent for communication campaign and data processing and they need to be able to withdraw it at any time. To fulfill this requirement, each field has a Privacy Preference entity that stores their preferences and all changes that occur along with the time and other information such the customer ip and browser information or alternatively, the full text of the privacy campaign once the user has accepted.
1. **Lapsed Interest Reviews**: This is used to determine the legitimate interest of an opportunity. When the plugin is installed, each time a new opportunity is created then an also an appropriate lapsed time is also created. The entity can then be reviewed and a decision made as to whether the elapsed time should be extended or information deleted after contacting the user account responsible for the opportunity. The user can then make a decision can be made whether the lapsed time should increased to allow further contact with the opportunity, or whether the opportunity should be deleted. 

## Installation

1. Obtain the plugin installer file: File name is *Unipart-GDPR-Plugin_Installer.zip* and you can find the latest version cloning this repo within the builds/latests/ folder.
1. Backup your SuiteCRM instance files and database, we also suggests to re-create a test one in order to deploy through blue/green methodology.
1. Login into the SuiteCRM instance with an account with administration privileges.
1. Navigate Admin -> Development tools -> Module Loader and upload the plugin.
1. Once successfully uploaded, click on install and follow the instructions.
1. After successfully install the plugin, please go into Admin -> repair and click on Quick Rebuild and Repair to complete the last post installation fixes.
1. Once successfully installed please ensure that: 
    1. Visit the GDPR - 1 - Text Field Types section to ensure you're monitoring the correct text fields.
    1. Visit the GDPR - 3 - Privacy Campaigns section and create the campaign needed for the business.

## How to run the behat tests
The test environment operations is managed with the project [Unipart Digital - SuiteCRM Dev Ops](https://github.com/unipart/suitecrm-dev-ops "Unipart Digital - SuiteCRM Dev Ops repository").

You can clone the repo, create the local development environment and run all the behaviour tests automatically in one command, from the project root you can type:
```
./bin/test.sh
```

For more information on the test.sh command you can type:
```
./bin/test.sh --help
```

**NOTE**: the behat test execution time is strictly correlated to the host machine power, for this reason we could have some false failure when a test fails because the execution time is greater than the wait one.

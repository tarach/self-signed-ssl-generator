Feature: Generate SSL by answering questions on command line

  Scenario: Generate certificate by answering questions
    When I execute command sslgen -vv
    And when asked about "Country Name" I answer "UK"
    And when asked about "State or province" I answer "Wales"
    And when asked about "Locality name" I answer "Newport"
    And when asked about "Organization Name" I answer "My Company ltd."
    And when asked about "Organization Unit Name" I answer "IT Department"
    And when asked about "Common Name" I answer "domain.com"
    And when asked about "Email address" I answer "email@address.com"
    Then displayed log message should contain:
        """
        Output directory set to
        Saving certificate signing request (CSR) as file [csr.req].
        Saving certificate as file [cert.pem].
        Saving private key as file [pkey.key].
        """

  Scenario: Generate certificate by answering questions using arguments
    When I execute command:
        """
        sslgen -vv -s --un=DE \
          --sp=Hesse \
          --ln=Frankfurt \
          --on="Name der Firma" \
          --oun="IT Abteilung" \
          --cn=domain.com \
          --ea=email@address.com
        """
    Then displayed log message should contain:
        """
        Output directory set to
        Saving certificate signing request (CSR) as file [csr.req].
        Saving certificate as file [cert.pem].
        Saving private key as file [pkey.key].
        """
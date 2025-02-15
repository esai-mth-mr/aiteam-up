<?php
// This file was auto-generated from sdk-root/src/data/appstream/2016-12-01/endpoint-tests-1.json
return [ 'testCases' => [ [ 'documentation' => 'For region ap-south-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-south-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'ap-south-1', ], ], [ 'documentation' => 'For region ap-south-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-south-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'ap-south-1', ], ], [ 'documentation' => 'For region ap-south-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-south-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'ap-south-1', ], ], [ 'documentation' => 'For region ap-south-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-south-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'ap-south-1', ], ], [ 'documentation' => 'For region us-gov-east-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-gov-east-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'us-gov-east-1', ], ], [ 'documentation' => 'For region us-gov-east-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-gov-east-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'us-gov-east-1', ], ], [ 'documentation' => 'For region us-gov-east-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-gov-east-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'us-gov-east-1', ], ], [ 'documentation' => 'For region us-gov-east-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-gov-east-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'us-gov-east-1', ], ], [ 'documentation' => 'For region ca-central-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ca-central-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'ca-central-1', ], ], [ 'documentation' => 'For region ca-central-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ca-central-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'ca-central-1', ], ], [ 'documentation' => 'For region ca-central-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ca-central-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'ca-central-1', ], ], [ 'documentation' => 'For region ca-central-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ca-central-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'ca-central-1', ], ], [ 'documentation' => 'For region eu-central-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.eu-central-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'eu-central-1', ], ], [ 'documentation' => 'For region eu-central-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.eu-central-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'eu-central-1', ], ], [ 'documentation' => 'For region eu-central-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.eu-central-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'eu-central-1', ], ], [ 'documentation' => 'For region eu-central-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.eu-central-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'eu-central-1', ], ], [ 'documentation' => 'For region us-west-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-west-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'us-west-1', ], ], [ 'documentation' => 'For region us-west-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-west-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'us-west-1', ], ], [ 'documentation' => 'For region us-west-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-west-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'us-west-1', ], ], [ 'documentation' => 'For region us-west-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-west-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'us-west-1', ], ], [ 'documentation' => 'For region us-west-2 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-west-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'us-west-2', ], ], [ 'documentation' => 'For region us-west-2 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-west-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'us-west-2', ], ], [ 'documentation' => 'For region us-west-2 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-west-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'us-west-2', ], ], [ 'documentation' => 'For region us-west-2 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-west-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'us-west-2', ], ], [ 'documentation' => 'For region eu-west-2 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.eu-west-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'eu-west-2', ], ], [ 'documentation' => 'For region eu-west-2 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.eu-west-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'eu-west-2', ], ], [ 'documentation' => 'For region eu-west-2 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.eu-west-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'eu-west-2', ], ], [ 'documentation' => 'For region eu-west-2 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.eu-west-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'eu-west-2', ], ], [ 'documentation' => 'For region eu-west-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.eu-west-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'eu-west-1', ], ], [ 'documentation' => 'For region eu-west-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.eu-west-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'eu-west-1', ], ], [ 'documentation' => 'For region eu-west-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.eu-west-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'eu-west-1', ], ], [ 'documentation' => 'For region eu-west-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.eu-west-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'eu-west-1', ], ], [ 'documentation' => 'For region ap-northeast-2 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-northeast-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'ap-northeast-2', ], ], [ 'documentation' => 'For region ap-northeast-2 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-northeast-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'ap-northeast-2', ], ], [ 'documentation' => 'For region ap-northeast-2 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-northeast-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'ap-northeast-2', ], ], [ 'documentation' => 'For region ap-northeast-2 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-northeast-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'ap-northeast-2', ], ], [ 'documentation' => 'For region ap-northeast-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-northeast-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'ap-northeast-1', ], ], [ 'documentation' => 'For region ap-northeast-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-northeast-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'ap-northeast-1', ], ], [ 'documentation' => 'For region ap-northeast-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-northeast-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'ap-northeast-1', ], ], [ 'documentation' => 'For region ap-northeast-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-northeast-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'ap-northeast-1', ], ], [ 'documentation' => 'For region sa-east-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.sa-east-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'sa-east-1', ], ], [ 'documentation' => 'For region sa-east-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.sa-east-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'sa-east-1', ], ], [ 'documentation' => 'For region sa-east-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.sa-east-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'sa-east-1', ], ], [ 'documentation' => 'For region sa-east-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.sa-east-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'sa-east-1', ], ], [ 'documentation' => 'For region us-gov-west-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-gov-west-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'us-gov-west-1', ], ], [ 'documentation' => 'For region us-gov-west-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-gov-west-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'us-gov-west-1', ], ], [ 'documentation' => 'For region us-gov-west-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-gov-west-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'us-gov-west-1', ], ], [ 'documentation' => 'For region us-gov-west-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-gov-west-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'us-gov-west-1', ], ], [ 'documentation' => 'For region ap-southeast-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-southeast-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'ap-southeast-1', ], ], [ 'documentation' => 'For region ap-southeast-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-southeast-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'ap-southeast-1', ], ], [ 'documentation' => 'For region ap-southeast-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-southeast-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'ap-southeast-1', ], ], [ 'documentation' => 'For region ap-southeast-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-southeast-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'ap-southeast-1', ], ], [ 'documentation' => 'For region ap-southeast-2 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-southeast-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'ap-southeast-2', ], ], [ 'documentation' => 'For region ap-southeast-2 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.ap-southeast-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'ap-southeast-2', ], ], [ 'documentation' => 'For region ap-southeast-2 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-southeast-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'ap-southeast-2', ], ], [ 'documentation' => 'For region ap-southeast-2 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.ap-southeast-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'ap-southeast-2', ], ], [ 'documentation' => 'For region us-east-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-east-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'us-east-1', ], ], [ 'documentation' => 'For region us-east-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-east-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'us-east-1', ], ], [ 'documentation' => 'For region us-east-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-east-1.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'us-east-1', ], ], [ 'documentation' => 'For region us-east-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-east-1.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'us-east-1', ], ], [ 'documentation' => 'For region us-east-2 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-east-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'us-east-2', ], ], [ 'documentation' => 'For region us-east-2 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.us-east-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'us-east-2', ], ], [ 'documentation' => 'For region us-east-2 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-east-2.api.aws', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'us-east-2', ], ], [ 'documentation' => 'For region us-east-2 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.us-east-2.amazonaws.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'us-east-2', ], ], [ 'documentation' => 'For region cn-northwest-1 with FIPS enabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.cn-northwest-1.api.amazonwebservices.com.cn', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => true, 'Region' => 'cn-northwest-1', ], ], [ 'documentation' => 'For region cn-northwest-1 with FIPS enabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2-fips.cn-northwest-1.amazonaws.com.cn', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'cn-northwest-1', ], ], [ 'documentation' => 'For region cn-northwest-1 with FIPS disabled and DualStack enabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.cn-northwest-1.api.amazonwebservices.com.cn', ], ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'cn-northwest-1', ], ], [ 'documentation' => 'For region cn-northwest-1 with FIPS disabled and DualStack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://appstream2.cn-northwest-1.amazonaws.com.cn', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'cn-northwest-1', ], ], [ 'documentation' => 'For custom endpoint with fips disabled and dualstack disabled', 'expect' => [ 'endpoint' => [ 'url' => 'https://example.com', ], ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => false, 'Region' => 'us-east-1', 'Endpoint' => 'https://example.com', ], ], [ 'documentation' => 'For custom endpoint with fips enabled and dualstack disabled', 'expect' => [ 'error' => 'Invalid Configuration: FIPS and custom endpoint are not supported', ], 'params' => [ 'UseDualStack' => false, 'UseFIPS' => true, 'Region' => 'us-east-1', 'Endpoint' => 'https://example.com', ], ], [ 'documentation' => 'For custom endpoint with fips disabled and dualstack enabled', 'expect' => [ 'error' => 'Invalid Configuration: Dualstack and custom endpoint are not supported', ], 'params' => [ 'UseDualStack' => true, 'UseFIPS' => false, 'Region' => 'us-east-1', 'Endpoint' => 'https://example.com', ], ], ], 'version' => '1.0',];

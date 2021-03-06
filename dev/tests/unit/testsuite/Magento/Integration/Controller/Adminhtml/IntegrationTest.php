<?php
/**
 * \Magento\Integration\Controller\Adminhtml
 *
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Integration\Controller\Adminhtml;

use Magento\Integration\Block\Adminhtml\Integration\Edit\Tab\Info;
use Magento\Integration\Model\Integration as IntegrationModel;
use Magento\View\Layout\Element as LayoutElement;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Magento\Integration\Controller\Adminhtml\Integration */
    protected $_controller;

    /** @var \Magento\TestFramework\Helper\ObjectManager $objectManagerHelper */
    protected $_objectManagerHelper;

    /** @var \Magento\ObjectManager|\PHPUnit_Framework_MockObject_MockObject */
    protected $_objectManagerMock;

    /** @var \Magento\Core\Model\App|\PHPUnit_Framework_MockObject_MockObject */
    protected $_appMock;

    /** @var \Magento\Core\Model\Layout\Filter\Acl|\PHPUnit_Framework_MockObject_MockObject */
    protected $_layoutFilterMock;

    /** @var \Magento\App\ConfigInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $_configMock;

    /** @var \Magento\Event\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $_eventManagerMock;

    /** @var \Magento\Translate|\PHPUnit_Framework_MockObject_MockObject */
    protected $_translateModelMock;

    /** @var \Magento\Backend\Model\Session|\PHPUnit_Framework_MockObject_MockObject */
    protected $_backendSessionMock;

    /** @var \Magento\Backend\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject */
    protected $_backendActionCtxMock;

    /** @var \Magento\Integration\Service\IntegrationV1|\PHPUnit_Framework_MockObject_MockObject */
    protected $_integrationSvcMock;

    /** @var \Magento\Integration\Service\OauthV1|\PHPUnit_Framework_MockObject_MockObject */
    protected $_oauthSvcMock;

    /** @var \Magento\Registry|\PHPUnit_Framework_MockObject_MockObject */
    protected $_registryMock;

    /** @var \Magento\App\Request\Http|\PHPUnit_Framework_MockObject_MockObject */
    protected $_requestMock;

    /** @var \Magento\App\Response\Http|\PHPUnit_Framework_MockObject_MockObject */
    protected $_responseMock;

    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $_messageManager;

    /** @var \Magento\Config\ScopeInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $_configScopeMock;

    /** @var \Magento\Integration\Helper\Data|\PHPUnit_Framework_MockObject_MockObject */
    protected $_integrationHelperMock;

    /** @var \Magento\App\ViewInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $_viewMock;

    /** @var \Magento\Core\Model\Layout\Merge|\PHPUnit_Framework_MockObject_MockObject */
    protected $_layoutMergeMock;

    /** @var \Magento\View\LayoutInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $_layoutMock;

    /**
     * @var \Magento\Escaper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_escaper;

    /** Sample integration ID */
    const INTEGRATION_ID = 1;

    /**
     * Setup object manager and initialize mocks
     */
    protected function setUp()
    {
        /** @var \Magento\TestFramework\Helper\ObjectManager $objectManagerHelper */
        $this->_objectManagerHelper = new \Magento\TestFramework\Helper\ObjectManager($this);
        $this->_objectManagerMock = $this->getMockBuilder('Magento\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        // Initialize mocks which are used in several test cases
        $this->_appMock = $this->getMockBuilder('Magento\Core\Model\App')
            ->setMethods(array('getConfig'))
            ->disableOriginalConstructor()
            ->getMock();
        $this->_configMock = $this->getMockBuilder('Magento\App\ConfigInterface')->disableOriginalConstructor()
            ->getMock();
        $this->_appMock->expects($this->any())->method('getConfig')->will($this->returnValue($this->_configMock));
        $this->_eventManagerMock = $this->getMockBuilder('Magento\Event\ManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_layoutFilterMock = $this->getMockBuilder('Magento\Core\Model\Layout\Filter\Acl')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_backendSessionMock = $this->getMockBuilder('Magento\Backend\Model\Session')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_translateModelMock = $this->getMockBuilder('Magento\TranslateInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_integrationSvcMock = $this->getMockBuilder('Magento\Integration\Service\IntegrationV1')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_oauthSvcMock = $this->getMockBuilder('Magento\Integration\Service\OauthV1')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_requestMock = $this->getMockBuilder('Magento\App\Request\Http')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_responseMock = $this->getMockBuilder('Magento\App\Response\Http')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_registryMock = $this->getMockBuilder('Magento\Registry')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_configScopeMock = $this->getMockBuilder('Magento\Config\ScopeInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_integrationHelperMock = $this->getMockBuilder('Magento\Integration\Helper\Data')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_messageManager = $this->getMockBuilder('Magento\Message\ManagerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_escaper = $this->getMockBuilder('Magento\Escaper')
            ->setMethods(array('escapeHtml'))
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testIndexAction()
    {
        $this->_verifyLoadAndRenderLayout();
        // renderLayout
        $this->_controller = $this->_createIntegrationController();
        $this->_controller->indexAction();
    }

    public function testNewAction()
    {
        $this->_verifyLoadAndRenderLayout();
        // verify the request is forwarded to 'edit' action
        $this->_requestMock->expects($this->any())->method('setActionName')->with('edit')
            ->will($this->returnValue($this->_requestMock));
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->newAction();
    }

    public function testEditAction()
    {
        $this->_integrationSvcMock
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo(self::INTEGRATION_ID))
            ->will($this->returnValue($this->_getSampleIntegrationData()));
        $this->_requestMock
            ->expects($this->any())
            ->method('getParam')
            ->with($this->equalTo(Integration::PARAM_INTEGRATION_ID))
            ->will($this->returnValue(self::INTEGRATION_ID));
        // put data in session, the magic function getFormData is called so, must match __call method name
        $this->_backendSessionMock->expects($this->any())
            ->method('__call')
            ->will($this->returnValueMap([
                ['setIntegrationData'],
                ['getIntegrationData', [
                    Info::DATA_ID => self::INTEGRATION_ID,
                    Info::DATA_NAME => 'testIntegration'
                ]]
            ]));
        $this->_verifyLoadAndRenderLayout();
        $controller = $this->_createIntegrationController();
        $controller->editAction();
    }

    public function testEditActionNonExistentIntegration()
    {
        $exceptionMessage = 'This integration no longer exists.';
        // verify the error
        $this->_messageManager->expects($this->once())
            ->method('addError')
            ->with($this->equalTo($exceptionMessage));
        $this->_requestMock->expects($this->any())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));
        // put data in session, the magic function getFormData is called so, must match __call method name
        $this->_backendSessionMock->expects($this->any())
            ->method('__call')->will($this->returnValue(array('name' => 'nonExistentInt')));

        $invalidIdException = new \Magento\Integration\Exception($exceptionMessage);
        $this->_integrationSvcMock->expects($this->any())
            ->method('get')->will($this->throwException($invalidIdException));
        $this->_verifyLoadAndRenderLayout();
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->editAction();
    }

    public function testEditActionNoDataAdd()
    {
        $exceptionMessage = 'Integration ID is not specified or is invalid.';
        // verify the error
        $this->_messageManager->expects($this->once())
            ->method('addError')
            ->with($this->equalTo($exceptionMessage));
        $this->_verifyLoadAndRenderLayout();
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->editAction();
    }

    public function testEditException()
    {
        $exceptionMessage = 'Integration ID is not specified or is invalid.';
        // verify the error
        $this->_messageManager->expects($this->once())
            ->method('addError')
            ->with($this->equalTo($exceptionMessage));
        $this->_controller = $this->_createIntegrationController();
        $this->_controller->editAction();
    }

    public function testSaveAction()
    {
        // Use real translate model
        $this->_translateModelMock = null;
        $this->_requestMock->expects($this->any())
            ->method('getPost')->will($this->returnValue([Integration::PARAM_INTEGRATION_ID => self::INTEGRATION_ID]));
        $this->_requestMock->expects($this->any())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));
        $intData = $this->_getSampleIntegrationData();
        $this->_integrationSvcMock
            ->expects($this->any())
            ->method('get')
            ->with(self::INTEGRATION_ID)
            ->will($this->returnValue($intData));
        $this->_integrationSvcMock->expects($this->any())->method('update')->with($this->anything())
            ->will($this->returnValue($intData));
        // verify success message
        $this->_messageManager->expects($this->once())->method('addSuccess')
            ->with(__('The integration \'%1\' has been saved.', $intData[Info::DATA_NAME]));
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->saveAction();
    }

    public function testSaveActionException()
    {
        $this->_requestMock->expects($this->any())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));

        // Have integration service throw an exception to test exception path
        $exceptionMessage = 'Internal error. Check exception log for details.';
        $this->_integrationSvcMock->expects($this->any())
            ->method('get')
            ->with(self::INTEGRATION_ID)
            ->will($this->throwException(new \Magento\Core\Exception($exceptionMessage)));
        // Verify error
        $this->_messageManager->expects($this->once())->method('addError')
            ->with($this->equalTo($exceptionMessage));
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->saveAction();
    }

    public function testSaveActionIntegrationException()
    {
        $this->_requestMock->expects($this->any())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));

        // Have integration service throw an exception to test exception path
        $exceptionMessage = 'Internal error. Check exception log for details.';
        $this->_integrationSvcMock->expects($this->any())
            ->method('get')
            ->with(self::INTEGRATION_ID)
            ->will($this->throwException(new \Magento\Integration\Exception($exceptionMessage)));
        // Verify error
        $this->_messageManager->expects($this->once())
            ->method('addError')
            ->with($this->equalTo($exceptionMessage));
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->saveAction();
    }

    public function testSaveActionNew()
    {
        $integration = $this->_getSampleIntegrationData();
        //No id when New Integration is Post-ed
        $integration->unsetData(array(IntegrationModel::ID, 'id'));
        $this->_requestMock->expects($this->any())->method('getPost')->will(
            $this->returnValue($integration->getData())
        );
        $integration->setData('id', self::INTEGRATION_ID);
        $this->_integrationSvcMock->expects($this->any())->method('create')->with($this->anything())
            ->will($this->returnValue($integration));
        $this->_integrationSvcMock->expects($this->any())->method('get')->with(self::INTEGRATION_ID)->will(
            $this->returnValue(null)
        );
        // Use real translate model
        $this->_translateModelMock = null;
        // verify success message
        $this->_messageManager->expects($this->once())->method('addSuccess')
            ->with(__('The integration \'%1\' has been saved.', $integration->getName()));
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->saveAction();
    }

    public function testSaveActionExceptionDuringServiceCreation()
    {
        $exceptionMessage = 'Service could not be saved.';
        $integration = $this->_getSampleIntegrationData();
        // No id when New Integration is Post-ed
        $integration->unsetData(array(IntegrationModel::ID, 'id'));
        $this->_requestMock->expects($this->any())->method('getPost')->will(
            $this->returnValue($integration->getData())
        );
        $integration->setData('id', self::INTEGRATION_ID);
        $this->_integrationSvcMock->expects($this->any())->method('create')->with($this->anything())
            ->will($this->throwException(new \Magento\Integration\Exception($exceptionMessage)));
        $this->_integrationSvcMock->expects($this->any())->method('get')->with(self::INTEGRATION_ID)->will(
            $this->returnValue(null)
        );
        // Use real translate model
        $this->_translateModelMock = null;
        // Verify success message
        $this->_messageManager->expects($this->once())
            ->method('addError')
            ->with($exceptionMessage);
        $integrationController = $this->_createIntegrationController();
        $integrationController->saveAction();
    }

    public function testDeleteAction()
    {
        $intData = $this->_getSampleIntegrationData();
        $this->_requestMock->expects($this->once())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));
        $this->_integrationSvcMock->expects($this->any())->method('get')->with($this->anything())
            ->will($this->returnValue($intData));
        $this->_integrationSvcMock->expects($this->any())->method('delete')->with($this->anything())
            ->will($this->returnValue($intData));
        // Use real translate model
        $this->_translateModelMock = null;
        // verify success message
        $this->_messageManager->expects($this->once())->method('addSuccess')
            ->with(__('The integration \'%1\' has been deleted.', $intData[Info::DATA_NAME]));
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->deleteAction();
    }

    public function testDeleteActionWithConsumer()
    {
        $intData = $this->_getSampleIntegrationData();
        $intData[Info::DATA_CONSUMER_ID] = 1;
        $this->_requestMock->expects($this->once())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));
        $this->_integrationSvcMock->expects($this->any())->method('get')->with($this->anything())
            ->will($this->returnValue($intData));
        $this->_integrationSvcMock->expects($this->once())->method('delete')->with($this->anything())
            ->will($this->returnValue($intData));
        $this->_oauthSvcMock->expects($this->once())->method('deleteConsumer')->with($this->anything())
            ->will($this->returnValue($intData));
        // Use real translate model
        $this->_translateModelMock = null;
        // verify success message
        $this->_messageManager->expects($this->once())->method('addSuccess')
            ->with(__('The integration \'%1\' has been deleted.', $intData[Info::DATA_NAME]));
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->deleteAction();
    }

    public function testDeleteActionConfigSetUp()
    {
        $intData = $this->_getSampleIntegrationData();
        $intData[Info::DATA_SETUP_TYPE] = IntegrationModel::TYPE_CONFIG;
        $this->_requestMock->expects($this->once())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));
        $this->_integrationSvcMock->expects($this->any())->method('get')->with($this->anything())
            ->will($this->returnValue($intData));
        $this->_integrationHelperMock->expects($this->once())->method('isConfigType')->with($intData)
            ->will($this->returnValue(true));
        // verify error message
        $this->_messageManager->expects($this->once())->method('addError')
            ->with(__('Uninstall the extension to remove integration \'%1\'.', $intData[Info::DATA_NAME]));
        $this->_integrationSvcMock->expects($this->never())->method('delete');
        // Use real translate model
        $this->_translateModelMock = null;
        // verify success message
        $this->_messageManager->expects($this->never())->method('addSuccess');
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->deleteAction();
    }

    public function testDeleteActionMissingId()
    {
        $this->_integrationSvcMock->expects($this->never())->method('get');
        $this->_integrationSvcMock->expects($this->never())->method('delete');
        // Use real translate model
        $this->_translateModelMock = null;
        // verify error message
        $this->_messageManager->expects($this->once())->method('addError')
            ->with(__('Integration ID is not specified or is invalid.'));
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->deleteAction();
    }

    public function testDeleteActionForServiceIntegrationException()
    {
        $intData = $this->_getSampleIntegrationData();
        $this->_integrationSvcMock->expects($this->any())->method('get')->with($this->anything())
            ->will($this->returnValue($intData));
        $this->_requestMock->expects($this->once())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));
        // Use real translate model
        $this->_translateModelMock = null;
        $exceptionMessage = __("Integration with ID '%1' doesn't exist.", $intData[Info::DATA_ID]);
        $invalidIdException = new \Magento\Integration\Exception($exceptionMessage);
        $this->_integrationSvcMock->expects($this->once())->method('delete')
            ->will($this->throwException($invalidIdException));
        $this->_messageManager->expects($this->once())->method('addError')
            ->with($exceptionMessage);
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->deleteAction();
    }

    public function testDeleteActionForServiceGenericException()
    {
        $intData = $this->_getSampleIntegrationData();
        $this->_integrationSvcMock->expects($this->any())->method('get')->with($this->anything())
            ->will($this->returnValue($intData));
        $this->_requestMock->expects($this->once())->method('getParam')->will($this->returnValue(self::INTEGRATION_ID));
        // Use real translate model
        $this->_translateModelMock = null;
        $exceptionMessage = __("Integration with ID '%1' doesn't exist.", $intData[Info::DATA_ID]);
        $invalidIdException = new \Exception($exceptionMessage);
        $this->_integrationSvcMock->expects($this->once())->method('delete')
            ->will($this->throwException($invalidIdException));
        //Generic Exception(non-Service) should never add the message in session for user display
        $this->_messageManager->expects($this->never())->method('addError');
        $integrationContr = $this->_createIntegrationController();
        $integrationContr->deleteAction();
    }

    public function testPermissionsDialog()
    {
        $controller = $this->_createIntegrationController();

        $this->_requestMock
            ->expects($this->any())
            ->method('getParam')
            ->with($this->equalTo(Integration::PARAM_INTEGRATION_ID))
            ->will($this->returnValue(self::INTEGRATION_ID));

        $this->_integrationSvcMock
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo(self::INTEGRATION_ID))
            ->will($this->returnValue($this->_getSampleIntegrationData()));

        // @codingStandardsIgnoreStart
        $handle = <<<HANDLE
<layout xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <handle id="adminhtml_integration_activate_permissions_webapi">
       <referenceBlock name="integration.activate.permissions.tabs">
          <block class="Magento\Webapi\Block\Adminhtml\Integration\Activate\Permissions\Tab\Webapi" name="integration_activate_permissions_tabs_webapi" template="integration/activate/permissions/tab/webapi.phtml"/>
          <action method="addTab">
             <argument name="name" xsi:type="string">integration_activate_permissions_tabs_webapi</argument>
             <argument name="block" xsi:type="string">integration_activate_permissions_tabs_webapi</argument>
          </action>
       </referenceBlock>
    </handle>
</layout>
HANDLE;
        // @codingStandardsIgnoreEnd

        $layoutUpdates = new LayoutElement($handle);
        $this->_registryMock->expects($this->any())->method('register');

        $this->_layoutMergeMock
            ->expects($this->once())
            ->method('getFileLayoutUpdatesXml')
            ->will($this->returnValue($layoutUpdates));

        $this->_viewMock
            ->expects($this->once())
            ->method('loadLayout')
            ->with($this->equalTo(['adminhtml_integration_activate_permissions_webapi']));

        $controller->permissionsDialogAction();
    }

    public function testTokensDialog()
    {
        $controller = $this->_createIntegrationController();
        $this->_registryMock->expects($this->any())->method('register');

        $this->_requestMock->expects($this->any())->method('getParam')->will($this->returnValueMap([
            [Integration::PARAM_INTEGRATION_ID, null, self::INTEGRATION_ID],
            [Integration::PARAM_REAUTHORIZE, 0, 0]
        ]));

        $this->_integrationSvcMock
            ->expects($this->any())
            ->method('get')
            ->with($this->equalTo(self::INTEGRATION_ID))
            ->will($this->returnValue($this->_getIntegrationModelMock()));

        $this->_oauthSvcMock->expects($this->once())->method('createAccessToken')->will($this->returnValue(true));

        $this->_viewMock->expects($this->any())->method('loadLayout');
        $this->_viewMock->expects($this->any())->method('renderLayout');

        $controller->tokensDialogAction();
    }

    public function testTokensExchangeReauthorize()
    {
        $controller = $this->_createIntegrationController();

        $this->_requestMock->expects($this->any())->method('getParam')->will($this->returnValueMap([
            [Integration::PARAM_INTEGRATION_ID, null, self::INTEGRATION_ID],
            [Integration::PARAM_REAUTHORIZE, 0, 1]
        ]));

        $this->_integrationSvcMock
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo(self::INTEGRATION_ID))
            ->will($this->returnValue($this->_getIntegrationModelMock()));

        $this->_oauthSvcMock->expects($this->once())->method('deleteToken');
        $this->_oauthSvcMock->expects($this->once())->method('postToConsumer');

        $this->_messageManager->expects($this->once())->method('addNotice');
        $this->_messageManager->expects($this->never())->method('addError');
        $this->_messageManager->expects($this->never())->method('addSuccess');

        $this->_viewMock->expects($this->once())->method('loadLayout');
        $this->_viewMock->expects($this->once())->method('renderLayout');

        $this->_responseMock->expects($this->once())->method('getBody');
        $this->_responseMock->expects($this->once())->method('setBody');

        $controller->tokensExchangeAction();
    }

    /**
     * Creates the IntegrationController to test.
     *
     * @return \Magento\Integration\Controller\Adminhtml\Integration
     */
    protected function _createIntegrationController()
    {
        // Mock Layout passed into constructor
        $this->_viewMock = $this->getMock('Magento\App\ViewInterface');
        $this->_layoutMock = $this->getMock('Magento\View\LayoutInterface');
        $this->_layoutMergeMock = $this->getMockBuilder('Magento\Core\Model\Layout\Merge')
            ->disableOriginalConstructor()
            ->getMock();
        $this->_layoutMock->expects($this->any())
            ->method('getUpdate')
            ->will($this->returnValue($this->_layoutMergeMock));
        $testElement = new \Magento\Simplexml\Element('<test>test</test>');
        $this->_layoutMock->expects($this->any())->method('getNode')->will($this->returnValue($testElement));
        // for _setActiveMenu
        $this->_viewMock->expects($this->any())->method('getLayout')->will($this->returnValue($this->_layoutMock));
        $blockMock = $this->getMockBuilder('Magento\Backend\Block\Menu')
            ->disableOriginalConstructor()
            ->getMock();
        $menuMock = $this->getMockBuilder('Magento\Backend\Model\Menu')
            ->disableOriginalConstructor()
            ->getMock();
        $loggerMock = $this->getMockBuilder('Magento\Logger')
            ->disableOriginalConstructor()
            ->getMock();
        $loggerMock->expects($this->any())->method('logException')->will($this->returnSelf());
        $menuMock->expects($this->any())->method('getParentItems')->will($this->returnValue(array()));
        $blockMock->expects($this->any())->method('getMenuModel')->will($this->returnValue($menuMock));
        $this->_layoutMock->expects($this->any())->method('getMessagesBlock')->will($this->returnValue($blockMock));
        $this->_layoutMock->expects($this->any())->method('getBlock')->will($this->returnValue($blockMock));
        $this->_escaper->expects($this->any())->method('escapeHtml')
            ->will($this->returnArgument(0));
        $contextParameters = array(
            'view'           => $this->_viewMock,
            'objectManager'  => $this->_objectManagerMock,
            'session'        => $this->_backendSessionMock,
            'translator'     => $this->_translateModelMock,
            'request'        => $this->_requestMock,
            'response'       => $this->_responseMock,
            'messageManager' => $this->_messageManager,
        );

        $this->_backendActionCtxMock = $this->_objectManagerHelper
            ->getObject(
                'Magento\Backend\App\Action\Context',
                $contextParameters
            );
        $subControllerParams = array(
            'context' => $this->_backendActionCtxMock,
            'integrationService' => $this->_integrationSvcMock,
            'oauthService' => $this->_oauthSvcMock,
            'registry' => $this->_registryMock,
            'logger' => $loggerMock,
            'integrationData' => $this->_integrationHelperMock,
            'escaper'        => $this->_escaper
        );
        /** Create IntegrationController to test */
        $controller = $this->_objectManagerHelper
            ->getObject(
                'Magento\Integration\Controller\Adminhtml\Integration',
                $subControllerParams
            );
        return $controller;
    }

    /**
     * Common mock 'expect' pattern.
     * Calls that need to be mocked out when
     * \Magento\Backend\Controller\AbstractAction loadLayout() and renderLayout() are called.
     */
    protected function _verifyLoadAndRenderLayout()
    {
        $map = array(
            array('Magento\App\ConfigInterface', $this->_configMock),
            array('Magento\Core\Model\Layout\Filter\Acl', $this->_layoutFilterMock),
            array('Magento\Backend\Model\Session', $this->_backendSessionMock),
            array('Magento\TranslateInterface', $this->_translateModelMock),
            array('Magento\Config\ScopeInterface', $this->_configScopeMock)
        );
        $this->_objectManagerMock->expects($this->any())
            ->method('get')
            ->will($this->returnValueMap($map));
    }

    /**
     * Return sample Integration Data
     *
     * @return \Magento\Object
     */
    protected function _getSampleIntegrationData()
    {
        return new \Magento\Object(array(
            Info::DATA_NAME => 'nameTest',
            Info::DATA_ID => self::INTEGRATION_ID,
            'id' => self::INTEGRATION_ID, // This will allow usage of both getIntegrationId() and getId()
            Info::DATA_EMAIL => 'test@magento.com',
            Info::DATA_ENDPOINT => 'http://magento.ll/endpoint',
            Info::DATA_SETUP_TYPE => IntegrationModel::TYPE_MANUAL
        ));
    }

    /**
     * Return integration model mock with sample data.
     *
     * @return \Magento\Integration\Model\Integration|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getIntegrationModelMock()
    {
        $integrationModelMock = $this->getMock(
            'Magento\Integration\Model\Integration', ['save', '__wakeup'], [], '', false
        );

        $integrationModelMock->expects($this->any())->method('setStatus')->will($this->returnSelf());
        $integrationModelMock
            ->expects($this->any())
            ->method('getData')
            ->will($this->returnValue($this->_getSampleIntegrationData()));

        return $integrationModelMock;
    }
}

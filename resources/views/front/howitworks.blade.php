@include('include.header')
<section class="inner-intro  padding bg-img1 overlay-dark light-color">
				<div class="container">
					<div class="row title">
						<h1>How it Works</h1>
						<div class="page-breadcrumb">
							<a>Home</a>/<span>How it works</span>
						</div>
					</div>
				</div>
			</section>

			<div id="mission-section" class="padding ptb-xs-60">
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
							<div class="heading-box pb-30">
								<h2>How <span>Bitcoin</span> Works ?</h2>
								<span class="b-line l-left"></span>
							</div>

						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="about-block clearfix">
								<div class="fl width-25per box-shadow  mb-xs-15">
									<img class="img-responsive" src="assets/images/work.jpg" alt="Photo" >
								</div>
								<div class="text-box pt-45 pb-15 pl-70 pl-xs-0 width-75per fl mt-xs-30">
									<div class="box-title">
										<h3>It's On Mission</h3>
									</div>
									<div class="text-content">
										<p>
											Bitcoin is the world’s first digital currency and payment network. It helps us connect financially just like the Internet has helped us connect socially. It is the modern way to send money. Bitcoin enables borderless, fast and cheap access to the world of finance.

										</p>
										<p>
											Aenean suscipit eget mi act fermentum phasellus vulputate turpis tincidunt. Aenean suscipit eget..
											Aenean suscipit eget mi act fermentum phasellus vulputate turpis tincidunt. Aenean suscipit eget
										</p>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>


			<!-- Story Section -->
			<div id="story-section" class="padding ptb-xs-60 gray-bg">
				<div class="container">
					<div class="row ">
						<div class="col-sm-12">
							<div class="heading-box pb-30 text-center">
								<h2><span>Flow</span> of Working</h2>
								<span class="b-line"></span>
							</div>

						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="ui-timline-container">
								<div class="ui-timeline">
									<div class="tl-item">
										<div class="tl-body">
											<div class="tl-entry">
												<div class="tl-caption">
													<a href="javascript:;" class="btn btn-primary btn-block">Start</a>
												</div>
											</div>
										</div>
									</div>
									<div class="tl-item">
										<div class="tl-body">
											<div class="tl-entry">
												<div class="tl-time">
												</div>
												<div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-info">
													<i class="fa fa-asterisk"></i>
												</div>
												<div class="tl-content">
													<h4 class="tl-tile text-primary">Mining</h4>
													<p>
														People are sending bitcoins to each other over the bitcoin network all the time, but unless someone keeps a record of all these transactions, no-one would be able to keep track of who had paid what. The bitcoin network deals with this by collecting all of the transactions made during a set period into a list, called a block. It’s the miners’ job to confirm those transactions, and write them into a general ledger.
													</p>
												</div>
											</div>
										</div>
									</div>
									<div class="tl-item alt">
										<div class="tl-body">
											<div class="tl-entry">
												<div class="tl-time">
												</div>
												<div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-warning">
													<i class="fa fa-shopping-cart"></i>
												</div>
												<div class="tl-content">
													<h4 class="tl-tile text-danger">Creating Hash</h4>
													<p>
														This general ledger is a long list of blocks, known as the 'blockchain'. It can be used to explore any transaction made between any bitcoin addresses, at any point on the network. Whenever a new block of transactions is created, it is added to the blockchain, creating an increasingly lengthy list of all the transactions that ever took place on the bitcoin network. A constantly updated copy of the block is given to everyone who participates, so that they know what is going on.
													</p>
													<p>
														But a general ledger has to be trusted, and all of this is held digitally. How can we be sure that the blockchain stays intact, and is never tampered with? This is where the miners come in. When a block of transactions is created, miners put it through a process. They take the information in the block, and apply a mathematical formula to it, turning it into something else. That something else is a far shorter, seemingly random sequence of letters and numbers known as a hash. This hash is stored along with the block, at the end of the blockchain at that point in time.

													</p>
													<p>
														Hashes have some interesting properties. It’s easy to produce a hash from a collection of data like a bitcoin block, but it’s practically impossible to work out what the data was just by looking at the hash. And while it is very easy to produce a hash from a large amount of data, each hash is unique. If you change just one character in a bitcoin block, its hash will change completely.

													</p>
													<p>

														Miners don’t just use the transactions in a block to generate a hash. Some other pieces of data are used too. One of these pieces of data is the hash of the last block stored in the blockchain.
													</p>

													<p>

														Because each block’s hash is produced using the hash of the block before it, it becomes a digital version of a wax seal. It confirms that this block – and every block after it – is legitimate, because if you tampered with it, everyone would know. If you tried to fake a transaction by changing a block that had already been stored in the blockchain, that block’s hash would change. If someone checked the block’s authenticity by running the hashing function on it, they’d find that the hash was different from the one already stored along with that block in the blockchain. The block would be instantly spotted as a fake.


													</p>

													<p>

														Because each block’s hash is used to help produce the hash of the next block in the chain, tampering with a block would also make the subsequent block’s hash wrong too. That would continue all the way down the chain, throwing everything out of whack.
													</p>

												</div>
											</div>
										</div>
									</div>
									<div class="tl-item">
										<div class="tl-body">
											<div class="tl-entry">
												<div class="tl-time">
												</div>
												<div class="tl-icon btn-icon-round btn-icon btn-icon-thin btn-success">
													<i class="fa fa-camera"></i>
												</div>
												<div class="tl-content">
													<h4 class="tl-tile text-warning">Competing for Coins</h4>
													<p>
														So, that’s how miners ‘seal off’ a block. They all compete with each other to do this, using software written specifically to mine blocks. Every time someone successfully creates a hash, they get a reward of 25 bitcoins, the blockchain is updated, and everyone on the network hears about it. That’s the incentive to keep mining, and keep the transactions working.
													</p>
													<p>
														The problem is that it’s very easy to produce a hash from a collection of data. Computers are really good at this. The bitcoin network has to make it more difficult, otherwise everyone would be hashing hundreds of transaction blocks each second, and all of the bitcoins would be mined in minutes. The bitcoin protocol deliberately makes it more difficult, by introducing something called ‘proof of work’.
													</p>
													<p>
														The bitcoin protocol won’t just accept any old hash. It demands that a block’s hash has to look a certain way; it must have a certain number of zeroes at the start. There’s no way of telling what a hash is going to look like before you produce it, and as soon as you include a new piece of data in the mix, the hash will be totally different.
													</p>
													<p>
														Miners aren’t supposed to meddle with the transaction data in a block, but they must change the data they’re using to create a different hash. They do this using another, random piece of data called a ‘nonce’. This is used with the transaction data to create a hash. If the hash doesn’t fit the required format, the nonce is changed, and the whole thing is hashed again. It can take many attempts to find a nonce that works, and all the miners in the network are trying to do it at the same time. That’s how miners earn their bitcoins.
													</p>

												</div>
											</div>
										</div>
									</div>
									<div class="tl-item">
										<div class="tl-body">
											<div class="tl-entry">
												<div class="tl-caption">
													<a href="javascript:;" class="btn btn-success btn-block">That's it</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

@include('include.footer')